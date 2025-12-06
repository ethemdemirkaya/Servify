<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Kullanıcı Listesi
     */
    public function index()
    {
        // Admin değilse engelle (Middleware'de yoksa burası ek güvenliktir)
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $users = User::orderBy('created_at', 'desc')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Yeni Kullanıcı Ekleme
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,waiter,chef,cashier',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true, // Varsayılan aktif
        ]);

        return redirect()->back()->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }

    /**
     * Kullanıcı Güncelleme
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,waiter,chef,cashier',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];

        // Eğer şifre alanı doluysa şifreyi de güncelle, boşsa elleme
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Kullanıcı bilgileri güncellendi.');
    }

    /**
     * Kullanıcı Silme
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Kendini silmeyi engelle
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Kendinizi silemezsiniz!');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Kullanıcı silindi.');
    }
}
