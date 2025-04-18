<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;

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
require __DIR__.'/auth.php';
