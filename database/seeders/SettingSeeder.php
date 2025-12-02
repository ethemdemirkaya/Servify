<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'Lezzet Restoran Yönetimi'
            ],
            [
                'key' => 'site_description',
                'value' => 'En iyi yemeklerin adresi.'
            ],
            [
                'key' => 'site_logo',
                'value' => 'assets/images/logo/default-logo.png' // Varsayılan yol
            ],
            [
                'key' => 'site_dark_logo',
                'value' => 'assets/images/logo/dark-logo.png'
            ],
            [
                'key' => 'site_light_logo',
                'value' => 'assets/images/logo/light-logo.png'
            ],
            [
                'key' => 'favicon',
                'value' => 'assets/images/logo/favicon.ico'
            ],
            [
                'key' => 'currency_symbol',
                'value' => '₺' // Para birimi sembolü
            ],
            [
                'key' => 'contact_email',
                'value' => 'info@restoran.com'
            ],
            [
                'key' => 'contact_phone',
                'value' => '+90 555 123 45 67'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']], // Key varsa güncelle
                ['value' => $setting['value']] // Yoksa oluştur
            );
        }
    }
}
