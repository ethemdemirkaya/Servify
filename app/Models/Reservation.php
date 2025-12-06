<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    // Toplu atama (Mass Assignment) için izin verilen alanlar
    protected $fillable = [
        'dining_table_id',
        'customer_name',
        'phone',
        'reservation_time',
        'guests_count',
        'status',
    ];

    /**
     * İLİŞKİ TANIMI: Bir rezervasyon bir masaya aittir.
     */
    public function diningTable()
    {
        // 'DiningTable' modeline 'dining_table_id' üzerinden bağlanır
        return $this->belongsTo(DiningTable::class, 'dining_table_id');
    }
}
