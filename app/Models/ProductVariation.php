<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;

    // Veritabanına toplu ekleme yapılabilmesi için izin verilen sütunlar
    protected $fillable = [
        'product_id',
        'name',
        'price_adjustment'
    ];

    /**
     * Bu varyasyonun hangi ürüne ait olduğunu belirtir.
     * Veritabanında 'product_id' sütununa bakar.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
