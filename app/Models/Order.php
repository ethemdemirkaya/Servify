<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
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

    // Siparişin içindeki ürünlere ulaşmak için (İleride lazım olur)
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
