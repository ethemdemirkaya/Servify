<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        // Tüm ayarları 'site_settings' adıyla cache'e alır.
        // Cache süresi: sonsuz (veritabanı değişene kadar)
        $settings = Cache::rememberForever('site_settings', function () {
            // Veritabanından [key => value] formatında çeker
            return Setting::pluck('value', 'key')->toArray();
        });

        // İstenen key varsa döndür, yoksa default değeri döndür
        return $settings[$key] ?? $default;
    }
}
