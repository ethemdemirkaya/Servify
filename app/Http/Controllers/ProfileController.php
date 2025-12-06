<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Profil sayfasını gösterir.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Kişisel bilgileri günceller (Ad, Email).
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Profil bilgileriniz başarıyla güncellendi.');
    }

    /**
     * Şifre güncelleme işlemini yapar.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:6|confirmed|different:current_password',
        ], [
            'current_password.current_password' => 'Mevcut şifreniz yanlış.',
            'password.confirmed' => 'Yeni şifreler eşleşmiyor.',
            'password.different' => 'Yeni şifre eskisiyle aynı olamaz.',
            'password.min' => 'Şifre en az 6 karakter olmalıdır.'
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Şifreniz başarıyla değiştirildi.');
    }
}
