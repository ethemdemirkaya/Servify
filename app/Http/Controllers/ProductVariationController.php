<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class ProductVariationController extends Controller
{
    public function index()
    {
        // Varyasyonları, bağlı olduğu ürünle birlikte çekiyoruz.
        // Created_at'e göre sıralayıp sayfalıyoruz (10 adet).
        $variations = ProductVariation::with('product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Modallarda ürün seçimi için tüm aktif ürünleri çekiyoruz
        $products = Product::where('is_active', 1)->get();

        return view('variations.index', compact('variations', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:191',
            // Fiyat farkı negatif de olabilir (indirimli varyasyon), o yüzden numeric yeterli
            'price_adjustment' => 'required|numeric',
        ]);

        ProductVariation::create([
            'product_id' => $request->product_id,
            'name' => $request->name,
            'price_adjustment' => $request->price_adjustment,
        ]);

        return redirect()->route('variations.index')->with('success', 'Varyasyon başarıyla eklendi.');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:191',
            'price_adjustment' => 'required|numeric',
        ]);

        $variation = ProductVariation::findOrFail($id);
        $variation->update([
            'product_id' => $request->product_id,
            'name' => $request->name,
            'price_adjustment' => $request->price_adjustment,
        ]);

        return redirect()->route('variations.index')->with('success', 'Varyasyon güncellendi.');
    }

    public function destroy(string $id)
    {
        $variation = ProductVariation::findOrFail($id);
        $variation->delete();

        return redirect()->route('variations.index')->with('success', 'Varyasyon silindi.');
    }
}
