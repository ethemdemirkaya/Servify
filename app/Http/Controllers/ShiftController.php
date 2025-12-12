<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\User; // User modelini eklemeyi unutma
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    public function index()
    {
        // 1. Giriş yapan kişinin AÇIK vardiyası var mı? (Ekranda göstermek için)
        $activeShift = Shift::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();

        // 2. Geçmiş vardiyaları listele
        // Eğer adminse hepsini görsün, değilse sadece kendi geçmişini görsün
        if (Auth::user()->role == 'admin') {
            $shifts = Shift::with('user')->latest()->get();
            // Admin için personel listesi (Vardiya başlatmak için)
            $users = User::where('is_active', true)->get();
        } else {
            $shifts = Shift::with('user')->where('user_id', Auth::id())->latest()->get();
            $users = []; // Boş dizi
        }

        return view('shifts.index', compact('shifts', 'activeShift', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'starting_cash' => 'required|numeric|min:0',
            'user_id' => 'nullable|exists:users,id' // Admin seçerse diye validation
        ]);

        // HEDEF KULLANICIYI BELİRLE
        // Eğer kullanıcı adminse ve birini seçtiyse O kişi, yoksa Kendisi.
        if (Auth::user()->role == 'admin' && $request->has('user_id') && $request->user_id != null) {
            $targetUserId = $request->user_id;
        } else {
            $targetUserId = Auth::id();
        }

        // Seçilen kişinin zaten açık vardiyası var mı?
        $exists = Shift::where('user_id', $targetUserId)->where('status', 'open')->exists();

        if ($exists) {
            // Eğer admin başkasına açıyorsa mesaj farklı olsun
            $msg = ($targetUserId == Auth::id())
                ? 'Zaten açık bir vardiyanız var.'
                : 'Bu personelin zaten açık bir vardiyası var.';

            return redirect()->back()->withErrors(['msg' => $msg]);
        }

        Shift::create([
            'user_id' => $targetUserId,
            'starting_cash' => $request->starting_cash,
            'start_time' => now(),
            'status' => 'open',
        ]);

        return redirect()->route('shifts.index')->with('success', 'Vardiya başarıyla başlatıldı.');
    }

    public function update(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);

        // Kapatma yetkisi: Ya kendi vardiyası olacak YA DA Admin olacak
        if ($shift->user_id != Auth::id() && Auth::user()->role != 'admin') {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $request->validate([
            'actual_cash' => 'required|numeric|min:0',
        ]);

        $shift->actual_cash = $request->actual_cash;
        $shift->end_time = now();
        $shift->status = 'closed';
        $shift->save();

        return redirect()->route('shifts.index')->with('success', 'Vardiya kapatıldı ve raporlandı.');
    }
}
