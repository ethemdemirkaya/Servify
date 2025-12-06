<?php

namespace App\Http\Controllers;

use App\Models\DiningTable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule; // Rule sınıfını eklemeyi unutmayın

class DiningTableController extends Controller
{
    public function index()
    {
        $tables = DiningTable::orderBy('name', 'asc')->get();
        return view('tables.index', compact('tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'capacity' => 'required|integer|min:1',
            // QR Kod boş olabilir ama doluysa benzersiz olmalı
            'qr_code' => 'nullable|string|max:191|unique:dining_tables,qr_code',
        ]);

        $table = new DiningTable();
        $table->name = $request->name;
        $table->capacity = $request->capacity;
        $table->status = 'empty';

        // EĞER QR KOD GİRİLDİYSE ONU KULLAN, YOKSA OTOMATİK OLUŞTUR
        if ($request->filled('qr_code')) {
            $table->qr_code = $request->qr_code;
        } else {
            $table->qr_code = 'TBL-' . Str::upper(Str::random(8));
        }

        $table->save();

        return redirect()->route('tables.index')->with('success', 'Masa başarıyla oluşturuldu.');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:empty,occupied,reserved',
            // Güncellemede kendi ID'sini hariç tutarak unique kontrolü yap
            'qr_code' => [
                'nullable',
                'string',
                'max:191',
                Rule::unique('dining_tables', 'qr_code')->ignore($id)
            ],
        ]);

        $table = DiningTable::findOrFail($id);
        $table->name = $request->name;
        $table->capacity = $request->capacity;
        $table->status = $request->status;

        // Güncellemede: Boş bırakırsa eskisi kalsın MI yoksa yeni mi üretilsin?
        // Genelde boş bırakılırsa "Otomatik üret" mantığı yeni kayıt içindir.
        // Ancak kullanıcı "silip yeni üretmek" isteyebilir.
        // Buradaki mantık: Doluysa güncelle, boşsa ve eskisi varsa dokunma, hiç yoksa üret.
        if ($request->filled('qr_code')) {
            $table->qr_code = $request->qr_code;
        } elseif (empty($table->qr_code)) {
            // Eğer veritabanında da yoksa (eski kayıtsa) oluştur
            $table->qr_code = 'TBL-' . Str::upper(Str::random(8));
        }
        // Not: Eğer kullanıcı mevcut QR kodunu silip otomatiğe döndürmek istiyorsa
        // input'u boş gönderdiğinde yukarıdaki mantık eskiyi korur.
        // Eğer tamamen resetlemek istiyorsanız buraya özel bir kontrol ekleyebilirsiniz.

        $table->save();

        return redirect()->route('tables.index')->with('success', 'Masa bilgileri güncellendi.');
    }

    public function destroy(string $id)
    {
        $table = DiningTable::findOrFail($id);
        if($table->status == 'occupied') {
            return back()->with('error', 'Dolu olan bir masayı silemezsiniz! Önce hesabı kapatın.');
        }
        $table->delete();
        return redirect()->route('tables.index')->with('success', 'Masa silindi.');
    }
}
