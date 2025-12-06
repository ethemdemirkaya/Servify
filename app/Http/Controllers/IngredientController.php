<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function index()
    {
        // Stok miktarı kritik seviyenin altında olanları belirginleştirmek için view tarafında kontrol edeceğiz.
        // Listeyi en son eklenene göre sıralayıp 10'arlı sayfalıyoruz.
        $ingredients = Ingredient::orderBy('created_at', 'desc')->paginate(10);

        return view('ingredients.index', compact('ingredients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'unit' => 'required|in:kg,g,l,ml,piece',
            'stock_quantity' => 'required|numeric|min:0',
            'alert_limit' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
        ]);

        Ingredient::create($request->all());

        return redirect()->route('ingredients.index')->with('success', 'Malzeme başarıyla eklendi.');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'unit' => 'required|in:kg,g,l,ml,piece',
            'stock_quantity' => 'required|numeric|min:0',
            'alert_limit' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
        ]);

        $ingredient = Ingredient::findOrFail($id);
        $ingredient->update($request->all());

        return redirect()->route('ingredients.index')->with('success', 'Malzeme güncellendi.');
    }

    public function destroy(string $id)
    {
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->delete();

        return redirect()->route('ingredients.index')->with('success', 'Malzeme silindi.');
    }
}
