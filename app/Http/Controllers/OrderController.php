<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Transaction;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(){
        $carts = Cart::with(['item','transaction'])->get();
        $transactions = Transaction::with('cart')->get();
        return view('order', compact('carts','transactions'));
    }
}
