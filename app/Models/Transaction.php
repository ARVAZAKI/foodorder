<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'name',
        'transaction_code',
        'qr_image',
        'total_price',
        'payment_status',
    ];

    public function cart()
    {
        return $this->hasMany(Cart::class, 'transaction_id', 'id');
    }
}
