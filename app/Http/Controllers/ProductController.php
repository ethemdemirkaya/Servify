<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        // get() yerine paginate(10) kullandık. Sayfa başına 10 ürün.
        $products = Product::with('category')->orderBy('created_at', 'desc')->paginate(10);

        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'required|boolean',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->is_active = $request->is_active;

        // Resim Yükleme İşlemi
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/products'), $filename);
            $product->image = 'uploads/products/' . $filename;
        }

        $product->save();

        return redirect()->route('products.index')->with('success', 'Ürün başarıyla eklendi.');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'required|boolean',
        ]);

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->is_active = $request->is_active;

        // Yeni Resim Varsa Eskisini Sil ve Yenisini Yükle
        if ($request->hasFile('image')) {
            // Eski resmi sil
            if ($product->image && File::exists(public_path($product->image))) {
                File::delete(public_path($product->image));
            }

            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/products'), $filename);
            $product->image = 'uploads/products/' . $filename;
        }

        $product->save();

        return redirect()->route('products.index')->with('success', 'Ürün güncellendi.');
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        // Resmi klasörden sil
        if ($product->image && File::exists(public_path($product->image))) {
            File::delete(public_path($product->image));
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Ürün silindi.');
    }
}
