<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiningTable extends Model
{
    use HasFactory;

    // Tablo adı 'dining_tables' olduğu için belirtelim (Laravel otomatik 'dining_tables' anlar ama garanti olsun)
    protected $table = 'dining_tables';

    protected $fillable = [
        'name',
        'capacity',
        'status',
        'qr_code'
    ];
}
