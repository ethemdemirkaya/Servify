<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'image', 'printer_id'];

    // Slug otomatik oluşturma (Mutator)
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Yazıcı İlişkisi (Opsiyonel)
    public function printer()
    {
        return $this->belongsTo(Printer::class);
    }

    // Ürünler İlişkisi
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
