<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/about-us', function () {
    return view('aboutus');
});
Route::get('/tes', function () {
    return view('tes');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('/products', ItemController::class);
Route::resource('/categories', CategoryController::class);
Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/order', [OrderController::class, 'index'])->name('order.index');

// Midtrans Routes
Route::post('/midtrans/notification', [TransactionController::class, 'notificationHandler'])->name('midtrans.notification');
Route::get('/midtrans/complete', [TransactionController::class, 'completePayment'])->name('midtrans.complete');

// Transaction Verification Routes
Route::get('/transactions/verify', function () {
    return view('transactions.verify_form');
})->name('transactions.verify_form');
Route::post('/transactions/verify', [TransactionController::class, 'verifyTransaction'])->name('transactions.verify');

require __DIR__.'/auth.php';