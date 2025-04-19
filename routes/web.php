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
Route::get('/complete', function () {
    return view('complete');
});
Route::resource('/products', ItemController::class)->middleware(['auth']);
Route::resource('/categories', CategoryController::class)->middleware(['auth']);
Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/dashboard', [OrderController::class, 'index'])->middleware(['auth'])->name('dashboard');
Route::get('/order-detail/{id}', [OrderController::class, 'show'])->middleware(['auth'])->name('order.show');
// Midtrans Routes
Route::post('/midtrans/notification', [TransactionController::class, 'notificationHandler'])->name('midtrans.notification');
Route::get('/midtrans/complete', [TransactionController::class, 'completePayment'])->name('midtrans.complete');

// Transaction Verification Routes
Route::get('/transactions/verify', function () {
    return view('transactions.verify_form');
})->name('transactions.verify_form');
Route::post('/transactions/verify', [TransactionController::class, 'verifyTransaction'])->name('transactions.verify');

Route::post('midtrans/notification', [App\Http\Controllers\TransactionController::class, 'handleNotification']);

require __DIR__.'/auth.php';