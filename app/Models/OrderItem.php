<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // Korumayı kaldırıyoruz (Tüm sütunlara veri yazılabilir)
    protected $guarded = [];

    // İLİŞKİLER (Opsiyonel ama faydalı)
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
