<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TransactionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/transactions/search', [OrderController::class, 'searchTransaction']);

Route::post('/midtrans/notification', [TransactionController::class, 'handleNotification']);
