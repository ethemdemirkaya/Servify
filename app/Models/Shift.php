<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'starting_cash',
        'actual_cash',
        'start_time',
        'end_time',
        'status', // open, closed
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'starting_cash' => 'decimal:2',
        'actual_cash' => 'decimal:2',
    ];

    // Vardiya kime ait
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Bu vardiya süresince yapılan NAKİT satış toplamı
    public function getCashSalesAttribute()
    {
        // Bitiş saati yoksa (hala açıksa) şu anı al
        $end = $this->end_time ?? now();

        return Payment::where('created_at', '>=', $this->start_time)
            ->where('created_at', '<=', $end)
            ->where('payment_method', 'cash') // Sadece nakit ödemeler kasayı ilgilendirir
            ->sum('amount');
    }

    // Beklenen Kasa Tutarı (Başlangıç + Satışlar)
    public function getExpectedCashAttribute()
    {
        return $this->starting_cash + $this->cash_sales;
    }

    // Kasa Farkı (Sayılan - Beklenen)
    public function getDifferenceAttribute()
    {
        if ($this->status == 'open') return 0;
        return $this->actual_cash - $this->expected_cash;
    }
}
