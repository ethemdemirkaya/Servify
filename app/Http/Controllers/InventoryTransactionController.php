<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryTransactionController extends Controller
{
    public function index()
    {
        // Hareketleri en yeniden eskiye sırala
        $transactions = InventoryTransaction::with(['ingredient', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Modalda seçim yapmak için malzemeler
        $ingredients = Ingredient::all();

        return view('inventory.transactions.index', compact('transactions', 'ingredients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'type' => 'required|in:purchase,sale,waste,adjustment',
            'quantity' => 'required|numeric|min:0.001',
            'description' => 'nullable|string|max:500',
        ]);

        // Transaction işlemi sırasında bir hata olursa veritabanını geri almak için Transaction bloğu kullanıyoruz.
        DB::transaction(function () use ($request) {

            // 1. Kaydı oluştur
            InventoryTransaction::create([
                'ingredient_id' => $request->ingredient_id,
                'user_id' => Auth::id() ?? 1, // Giriş yapmış kullanıcı yoksa geçici olarak 1 (Admin) atanır
                'quantity' => $request->quantity,
                'type' => $request->type,
                'description' => $request->description,
            ]);

            // 2. Malzemenin stok miktarını güncelle
            $ingredient = Ingredient::findOrFail($request->ingredient_id);

            if ($request->type == 'purchase') {
                // Satın almada stok artar
                $ingredient->increment('stock_quantity', $request->quantity);
            } elseif ($request->type == 'waste' || $request->type == 'sale') {
                // Zayi veya Satışta stok azalır
                $ingredient->decrement('stock_quantity', $request->quantity);
            } elseif ($request->type == 'adjustment') {
                // Düzeltme (Sayım farkı): Bu örnekte pozitif girilirse ekler, negatif girilirse çıkarır diye varsayıyoruz
                // Veya sadece ekleme olarak kullanıp kullanıcıya eksi girmesini söyleyebilirsiniz.
                // Biz burada direkt toplama yapalım (Kullanıcı -5 girerse stok düşer).
                $ingredient->stock_quantity += $request->quantity;
                $ingredient->save();
            }

            // Eğer stok eksiye düştüyse ve buna izin vermiyorsanız burada kontrol edebilirsiniz.
            // Şimdilik izin veriyoruz.
        });

        return redirect()->route('inventory.transactions.index')->with('success', 'Stok hareketi işlendi ve bakiye güncellendi.');
    }

    public function destroy(string $id)
    {
        // Stok hareketini silmek stoğu geri almalı mı?
        // Genelde muhasebesel olarak kayıt silinmez, "İptal" kaydı atılır.
        // Ancak basit bir sistemde silmeye izin veriyorsak sadece kaydı siliyoruz (Stok geri alınmıyor uyarısı verilebilir).

        $transaction = InventoryTransaction::findOrFail($id);
        $transaction->delete();

        return redirect()->route('inventory.transactions.index')->with('success', 'Kayıt silindi. (Dikkat: Stok miktarı otomatik geri alınmadı, gerekirse manuel düzeltme yapınız.)');
    }
}
