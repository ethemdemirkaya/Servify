<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unit',            // enum: kg, g, l, ml, piece
        'stock_quantity',
        'alert_limit',
        'cost_price'
    ];
}
