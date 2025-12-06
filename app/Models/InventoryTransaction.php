<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'ingredient_id',
        'user_id',
        'quantity',
        'type', // purchase, sale, waste, adjustment
        'description'
    ];

    // Hangi malzeme?
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    // İşlemi yapan personel kim?
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
