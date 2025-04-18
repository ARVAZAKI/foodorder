<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Transaction;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(){
        $carts = Cart::with(['item','transaction'])->get();
        
        // Load transaction dengan relationship cart dan item
        $transactions = Transaction::with('cart.item')
            ->orderBy('created_at', 'desc')->where('payment_status', 'paid')
            ->paginate(10);
        
        // Siapkan status pembayaran untuk tampilan
        $paymentStatusLabels = [
            'pending' => 'Menunggu Pembayaran',
            'challenge' => 'Tantangan Pembayaran',
            'paid' => 'Pembayaran Sukses',
            'failed' => 'Pembayaran Gagal',
            'expired' => 'Pembayaran Kedaluwarsa'
        ];
        
        $paymentStatusBadges = [
            'pending' => 'warning',
            'challenge' => 'info',
            'paid' => 'success',
            'failed' => 'danger',
            'expired' => 'secondary'
        ];
        
        return view('order', compact('carts', 'transactions', 'paymentStatusLabels', 'paymentStatusBadges'));
    }
    
    // Menambahkan method untuk melihat detail transaksi
    public function show($id)
    {
        $transaction = Transaction::with('cart.item')->findOrFail($id);
        
        // Siapkan status pembayaran untuk tampilan
        $paymentStatusLabels = [
            'pending' => 'Menunggu Pembayaran',
            'challenge' => 'Tantangan Pembayaran',
            'paid' => 'Pembayaran Sukses',
            'failed' => 'Pembayaran Gagal',
            'expired' => 'Pembayaran Kedaluwarsa'
        ];
        
        $paymentStatusBadges = [
            'pending' => 'warning',
            'challenge' => 'info',
            'paid' => 'success',
            'failed' => 'danger',
            'expired' => 'secondary'
        ];
        
        return view('order-detail', compact('transaction', 'paymentStatusLabels', 'paymentStatusBadges'));
    }
}