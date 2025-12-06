<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'dining_table_id',
        'customer_name',
        'total_amount',
        'status',
        'payment_status',
        'note'
    ];
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Kasiyer ve Garson ekranında masa ismini çekmek için bu da lazım olacak
    public function diningTable(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DiningTable::class, 'dining_table_id');
    }
    public function table()
    {
        // 2. parametre olarak 'dining_table_id' veriyoruz çünkü fonksiyon adımız 'table'
        return $this->belongsTo(DiningTable::class, 'dining_table_id');
    }
    // Siparişin içindeki ürünlere ulaşmak için (İleride lazım olur)
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
