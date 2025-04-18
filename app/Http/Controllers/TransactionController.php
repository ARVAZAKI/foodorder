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
            'name' => 'required|string|max:255',
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

        // Generate kode transaksi
        $transactionCode = Str::upper(Str::random(5));

        // Simpan transaksi awal (tanpa QR)
        $transaction = Transaction::create([
            'name' => $request->name,
            'transaction_code' => $transactionCode,
            'total_price' => $totalPrice,
            'payment_status' => 'pending',
        ]);

        // Simpan cart
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
