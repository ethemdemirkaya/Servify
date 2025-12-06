<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Ingredient;
use App\Models\ProductIngredient;
use Illuminate\Http\Request;

class ProductRecipeController extends Controller
{
    public function index()
    {
        // Reçeteleri ürün ve malzeme bilgileriyle çekiyoruz
        $recipes = ProductIngredient::with(['product', 'ingredient'])
            ->orderBy('product_id') // Ürünlere göre gruplu gibi dursun
            ->paginate(10);

        // Modallar için aktif ürünler ve malzemeler
        $products = Product::where('is_active', 1)->get();
        $ingredients = Ingredient::all();

        return view('product-recipes.index', compact('recipes', 'products', 'ingredients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'ingredient_id' => 'required|exists:ingredients,id',
            'quantity' => 'required|numeric|min:0.001',
        ]);

        // Aynı ürüne aynı malzeme daha önce eklenmiş mi kontrol et
        $exists = ProductIngredient::where('product_id', $request->product_id)
            ->where('ingredient_id', $request->ingredient_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['msg' => 'Bu malzeme bu ürüne zaten eklenmiş. Lütfen mevcut kaydı düzenleyin.']);
        }

        ProductIngredient::create($request->all());

        return redirect()->route('product-recipes.index')->with('success', 'Reçete satırı eklendi.');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'ingredient_id' => 'required|exists:ingredients,id',
            'quantity' => 'required|numeric|min:0.001',
        ]);

        $recipe = ProductIngredient::findOrFail($id);

        // Eğer ürün veya malzeme değiştiyse ve yeni kombinasyon veritabanında varsa hata ver (Kendi ID'si hariç)
        $exists = ProductIngredient::where('product_id', $request->product_id)
            ->where('ingredient_id', $request->ingredient_id)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['msg' => 'Bu malzeme bu üründe zaten mevcut.']);
        }

        $recipe->update($request->all());

        return redirect()->route('product-recipes.index')->with('success', 'Reçete güncellendi.');
    }

    public function destroy(string $id)
    {
        $recipe = ProductIngredient::findOrFail($id);
        $recipe->delete();

        return redirect()->route('product-recipes.index')->with('success', 'Reçete satırı silindi.');
    }
}
