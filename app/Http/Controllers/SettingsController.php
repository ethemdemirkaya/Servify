<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    public function index()
    {
        // Tüm ayarları key => value formatında diziye çeviriyoruz
        // Örn: ['site_name' => 'Servify', 'currency_symbol' => '₺']
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // 1. Genel Metin Ayarları
        $textSettings = [
            'site_name',
            'site_description',
            'currency_symbol',
            'contact_email',
            'contact_phone'
        ];

        foreach ($textSettings as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $request->input($key)]
            );
        }

        // 2. Dosya/Resim Yükleme İşlemleri
        $fileSettings = ['site_logo', 'site_dark_logo', 'site_light_logo', 'favicon'];

        foreach ($fileSettings as $key) {
            if ($request->hasFile($key)) {
                // Dosya nesnesini al
                $file = $request->file($key);

                // Klasör yolu
                $path = 'uploads/settings';

                // Dosya adı (random veya key ismiyle)
                $filename = $key . '_' . time() . '.' . $file->getClientOriginalExtension();

                // Mevcut eski dosyayı bul ve sil
                $oldSetting = Setting::where('key', $key)->first();
                if ($oldSetting && $oldSetting->value && File::exists(public_path($oldSetting->value))) {
                    File::delete(public_path($oldSetting->value));
                }

                // Yeni dosyayı yükle
                $file->move(public_path($path), $filename);

                // Veritabanını güncelle
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $path . '/' . $filename]
                );
            }
        }

        return redirect()->back()->with('success', 'Ayarlar başarıyla güncellendi.');
    }
}
