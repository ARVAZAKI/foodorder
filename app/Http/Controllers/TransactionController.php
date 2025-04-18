<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Cart;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
        try {
            Config::$serverKey = config('services.midtrans.server_key');
            $notification = new Notification();

            $orderId = $notification->order_id;
            $status = $notification->transaction_status;
            $transaction = Transaction::where('transaction_code', $orderId)->first();

            if (!$transaction) {
                Log::error('Transaksi tidak ditemukan untuk notifikasi', ['order_id' => $orderId]);
                return response()->json(['status' => 'error'], 404);
            }

            // Update status pembayaran
            if ($status == 'settlement') {
                $transaction->payment_status = 'paid';
            } elseif ($status == 'pending') {
                $transaction->payment_status = 'pending';
            } elseif (in_array($status, ['deny', 'expire', 'cancel'])) {
                $transaction->payment_status = 'failed';
            }

            $transaction->save();
            Log::info('Status pembayaran diperbarui', [
                'order_id' => $orderId,
                'status' => $status,
                'payment_status' => $transaction->payment_status
            ]);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Gagal memproses notifikasi Midtrans', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error'], 500);
        }
    }
}