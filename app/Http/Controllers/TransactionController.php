<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'items' => 'required|array',
        'items.*.item_id' => 'required|exists:items,id',
        'items.*.quantity' => 'required|integer|min:1',
    ]);

    $transactionCode = Str::upper(Str::random(10)); // Panjang kode ditambah untuk lebih unik
    $totalPrice = 0;

    foreach ($request->items as $item) {
        $product = Item::findOrFail($item['item_id']);
        $totalPrice += $product->price * $item['quantity'];
    }

    $transaction = Transaction::create([
        'transaction_code' => $transactionCode,
        'qr_image' => '',
        'total_price' => $totalPrice,
    ]);

    // Data yang lebih lengkap untuk QR code
    $qrCodeContent = json_encode([
        'transaction_code' => $transactionCode,
        'total_price' => $totalPrice,
        'created_at' => now()->toDateTimeString(),
    ]);
    
    $qrFileName = 'qrcodes/' . $transactionCode . '.png';
    
    // Pastikan direktori qrcodes ada
    if (!Storage::exists('public/qrcodes')) {
        Storage::makeDirectory('public/qrcodes');
    }

    // Generate QR code dengan SimpleSoftwareIO/simple-qrcode
    $qrCode = new QrCode($qrCodeContent);
$writer = new PngWriter();
$result = $writer->write($qrCode);

$qrFileName = 'qrcodes/' . $transactionCode . '.png';

if (!Storage::exists('public/qrcodes')) {
    Storage::makeDirectory('public/qrcodes');
}

Storage::put('public/' . $qrFileName, $result->getString());

$transaction->update([
    'qr_image' => $qrFileName,
]);
    $transaction->update([
        'qr_image' => $qrFileName,
    ]);

    foreach ($request->items as $item) {
        Cart::create([
            'transaction_id' => $transaction->id,
            'item_id' => $item['item_id'],
            'quantity' => $item['quantity'],
        ]);
    }

    // Tambahkan data ke response untuk ditampilkan
    return redirect()->back()->with([
        'success' => 'Transaction created successfully',
        'transaction' => $transaction,
        'qr_url' => asset('storage/' . $qrFileName)
    ]);
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
