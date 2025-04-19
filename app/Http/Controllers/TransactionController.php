<?php
namespace App\Http\Controllers;

use Midtrans\Snap;
use App\Models\Cart;
use App\Models\Item;
use Midtrans\Config;
use Endroid\QrCode\QrCode;
use Midtrans\Notification;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Hitung total harga
        $totalPrice = 0;
        foreach ($request->items as $itemInput) {
            $item = Item::findOrFail($itemInput['item_id']);
            Log::info('Item ditemukan', [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => $itemInput['quantity']
            ]);
            // Validasi harga item
            if ($item->price <= 0) {
                Log::error('Harga item tidak valid', [
                    'item_id' => $item->id,
                    'price' => $item->price
                ]);
                return response()->json(['error' => 'Harga item tidak valid'], 400);
            }
            $totalPrice += $item->price * $itemInput['quantity'];
        }

        // Validasi total harga
        if ($totalPrice <= 0) {
            Log::error('Total harga tidak valid', ['totalPrice' => $totalPrice]);
            return response()->json(['error' => 'Total harga tidak valid'], 400);
        }

        // Generate kode transaksi unik
        do {
            $transactionCode = Str::upper(Str::random(10));
        } while (Transaction::where('transaction_code', $transactionCode)->exists());

        // Simpan transaksi
        try {
            $transaction = Transaction::create([
                'name' => $request->name,
                'transaction_code' => $transactionCode,
                'total_price' => $totalPrice,
                'payment_status' => 'pending',
            ]);
            Log::info('Transaksi berhasil disimpan', ['transaction_id' => $transaction->id]);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan transaksi', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Gagal menyimpan transaksi'], 500);
        }

        // Simpan cart
        try {
            foreach ($request->items as $itemInput) {
                Cart::create([
                    'item_id' => $itemInput['item_id'],
                    'quantity' => $itemInput['quantity'],
                    'transaction_id' => $transaction->id,
                ]);
            }
            Log::info('Cart berhasil disimpan', ['transaction_id' => $transaction->id]);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan cart', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Gagal menyimpan cart'], 500);
        }

        // Generate QR Code
        try {
            $qrCode = QrCode::create($transactionCode)
                ->setSize(300)
                ->setMargin(10);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            $qrPath = 'qrcodes/' . $transactionCode . '.png';

            // Simpan QR code
            if (!Storage::disk('public')->put($qrPath, $result->getString())) {
                Log::error('Gagal menyimpan QR code', ['path' => $qrPath]);
                return response()->json(['error' => 'Gagal menyimpan QR code'], 500);
            }
            $qrUrl = Storage::url($qrPath);
            Log::info('QR code berhasil disimpan', ['path' => $qrPath, 'url' => $qrUrl]);
        } catch (\Exception $e) {
            Log::error('Gagal membuat QR code', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Gagal membuat QR code'], 500);
        }

        // Konfigurasi Midtrans
        try {
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$clientKey = config('services.midtrans.client_key');
            Config::$isProduction = config('services.midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            Log::info('Konfigurasi Midtrans', [
                'serverKey' => substr(config('services.midtrans.server_key'), 0, 10) . '...',
                'clientKey' => substr(config('services.midtrans.client_key'), 0, 10) . '...',
                'isProduction' => config('services.midtrans.is_production'),
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengatur konfigurasi Midtrans', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Konfigurasi Midtrans tidak valid'], 500);
        }

        // Buat parameter transaksi Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $transactionCode,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $request->name,
            ],
            'item_details' => array_map(function ($itemInput) {
                $item = Item::findOrFail($itemInput['item_id']);
                return [
                    'id' => $item->id,
                    'price' => $item->price,
                    'quantity' => $itemInput['quantity'],
                    'name' => $item->name,
                ];
            }, $request->items),
            'enabled_payments' => ['qris', 'gopay', 'shopeepay', 'bank_transfer', 'credit_card'],
        ];

        Log::info('Parameter Midtrans Snap', $params);

        // Dapatkan Snap Token
        try {
            $snapToken = Snap::getSnapToken($params);
            Log::info('Snap Token berhasil dibuat', ['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Gagal membuat Snap Token', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Gagal membuat transaksi di Midtrans: ' . $e->getMessage()], 500);
        }

        // Kembalikan respons JSON
        $response = [
            'success' => 'Transaksi dibuat, silakan selesaikan pembayaran!',
            'transaction' => $transaction,
            'snap_token' => $snapToken,
            'total_price' => $totalPrice,
            'qr_url' => $qrUrl,
        ];
        Log::info('Respons JSON dikembalikan', $response);
        return response()->json($response);
    }

    // Halaman complete (opsional, untuk redirect setelah pembayaran)
    public function complete(Request $request)
    {
        $orderId = $request->query('order_id');
        $transaction = Transaction::where('transaction_code', $orderId)->first();

        if (!$transaction) {
            Log::error('Transaksi tidak ditemukan', ['order_id' => $orderId]);
            return view('midtrans.error', ['message' => 'Transaksi tidak ditemukan']);
        }

        // Ambil QR code
        $qrPath = 'qrcodes/' . $transaction->transaction_code . '.png';
        $qrUrl = Storage::disk('public')->exists($qrPath) ? Storage::url($qrPath) : null;

        Log::info('Halaman complete diakses', [
            'transaction_id' => $transaction->id,
            'qr_url' => $qrUrl
        ]);

        return view('midtrans.complete', compact('transaction', 'qrUrl'));
    }

    // Webhook untuk memperbarui status pembayaran
    public function handleNotification(Request $request)
{
    Log::info('Webhook dari Midtrans diterima', ['request' => $request->all()]);
    
    try {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        
        // Tangkap raw input dari Midtrans
        $input = file_get_contents('php://input');
        Log::info('Raw input dari Midtrans', ['input' => $input]);
        
        // Decode JSON input menjadi array
        $notificationArray = json_decode($input, true);
        
        // Buat objek notification dengan input JSON
        $notification = new Notification();
        
        // Ekstrak data penting dari array, bukan dari objek yang belum diinisialisasi
        $orderId = $notificationArray['order_id'] ?? null;
        $transactionStatus = $notificationArray['transaction_status'] ?? null;
        $fraudStatus = $notificationArray['fraud_status'] ?? null;
        $paymentType = $notificationArray['payment_type'] ?? 'unknown';
        
        Log::info('Data transaksi dari Midtrans', [
            'order_id' => $orderId,
            'status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'payment_type' => $paymentType
        ]);
        
        // Validasi data penting
        if (!$orderId || !$transactionStatus) {
            throw new \Exception('Data transaksi tidak lengkap');
        }
        
        // Cari transaksi berdasarkan kode
        $transaction = Transaction::where('transaction_code', $orderId)->firstOrFail();
        
        // Update status transaksi
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $transaction->payment_status = 'challenge';
            } else if ($fraudStatus == 'accept') {
                $transaction->payment_status = 'paid';
            }
        } else if ($transactionStatus == 'settlement') {
            $transaction->payment_status = 'paid';
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $transaction->payment_status = 'failed';
        } else if ($transactionStatus == 'pending') {
            $transaction->payment_status = 'pending';
        }
        
        
        // Simpan transaksi
        $transaction->save();
        
        Log::info('Status transaksi diperbarui', [
            'order_id' => $orderId,
            'old_status' => $transaction->getOriginal('payment_status'),
            'new_status' => $transaction->payment_status
        ]);
        
        return response()->json(['status' => 'success']);
    } catch (\Exception $e) {
        Log::error('Gagal memproses notifikasi Midtrans', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request' => $request->all()
        ]);
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}
}