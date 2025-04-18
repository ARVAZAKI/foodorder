<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;


class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $totalPrice = 0;

        // Hitung total harga
        foreach ($request->items as $itemInput) {
            $item = Item::findOrFail($itemInput['item_id']);
            $totalPrice += $item->price * $itemInput['quantity'];
        }

        // Generate kode transaksi random 5 huruf kapital
        $transactionCode = Str::upper(Str::random(5));

        // Simpan transaksi terlebih dahulu tanpa QR
        $transaction = Transaction::create([
            'name' => $request->name,
            'transaction_code' => $transactionCode,
            'qr_image' => '', // Placeholder
            'total_price' => $totalPrice,
            'payment_status' => 'pending',
        ]);

        // Generate QR code image dari kode transaksi
        $qrSvg = QrCode::format('svg')->size(300)->generate($transactionCode);
        
        // Simpan QR image ke storage (misalnya: storage/app/public/qrcodes/)
        $qrImageName = 'qrcodes/' . $transactionCode . '.png';
        Storage::disk('public')->put('qrcodes/' . $transactionCode . '.svg', $qrSvg);

        

        // Update transaksi dengan path qr_image
        $transaction->update([
            'qr_image' => $qrImageName,
        ]);


        // Simpan semua cart
        foreach ($request->items as $itemInput) {
            Cart::create([
                'item_id' => $itemInput['item_id'],
                'quantity' => $itemInput['quantity'],
                'transaction_id' => $transaction->id,
            ]);
        }

        return redirect()->back()->with([
            'success' => 'Transaksi berhasil dibuat!',
            'transaction' => $transaction,
            'qr_url' => asset('storage/' . $transaction->qr_image),
        ]);
    }
}
