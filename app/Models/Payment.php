<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'payment_method', // cash, credit_card, online, other
    ];

    // Ödemenin ait olduğu sipariş
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
