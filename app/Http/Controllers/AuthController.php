<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Giriş sayfasını göster
    public function showLogin()
    {
        return view('auth.login'); // Dosya yoluna göre düzenle (örn: resources/views/auth/login.blade.php)
    }

    // Giriş işlemini yap
    public function login(Request $request)
    {
        // 1. Doğrulama
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'E-posta adresi zorunludur.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'password.required' => 'Şifre zorunludur.',
        ]);

        // 2. Beni Hatırla kontrolü
        $remember = $request->has('remember');

        // 3. Giriş Denemesi
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard'); // Giriş başarılıysa yönlendirilecek yer
        }

        // 4. Hata Döndürme
        return back()->withErrors([
            'email' => 'Girilen bilgiler kayıtlarımızla eşleşmiyor.',
        ])->onlyInput('email');
    }

    // Çıkış Yap
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
