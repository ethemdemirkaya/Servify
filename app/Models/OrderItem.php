<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'sub_total',
        'status',
        'note'
    ];

    /**
     * Bu sipariş kalemi hangi ürüne ait?
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Bu sipariş kaleminin varyasyonları (Örn: Büyük Boy, Acılı)
     * Veritabanı tablosu: order_item_variations
     * Foreign Key: order_item_id
     */
    public function variations()
    {
        return $this->hasMany(OrderItemVariation::class, 'order_item_id');
    }

    /**
     * Hangi siparişe ait?
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
