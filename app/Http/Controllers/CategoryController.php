<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Printer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // app/Http/Controllers/CategoryController.php

    public function index()
    {
        // Kategorileri, bağlı oldukları yazıcı ve ürün sayısıyla beraber çekiyoruz.
        // Sayfalama: 10 kayıt
        $categories = Category::with('printer')
            ->withCount('products')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Modallarda kullanmak için yazıcıları çekiyoruz
        $printers = \App\Models\Printer::all();

        return view('categories.index', compact('categories', 'printers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191|unique:categories,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'printer_id' => 'nullable|exists:printers,id'
        ]);

        $category = new Category();
        $category->name = $request->name; // Slug modelde otomatik oluşur
        $category->printer_id = $request->printer_id;

        // Resim Yükleme
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'cat_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/categories'), $filename);
            $category->image = 'uploads/categories/' . $filename;
        }

        $category->save();

        return redirect()->route('categories.index')->with('success', 'Kategori başarıyla oluşturuldu.');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:191|unique:categories,name,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'printer_id' => 'nullable|exists:printers,id'
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->name; // Slug güncellenir
        $category->printer_id = $request->printer_id;

        if ($request->hasFile('image')) {
            if ($category->image && File::exists(public_path($category->image))) {
                File::delete(public_path($category->image));
            }
            $file = $request->file('image');
            $filename = 'cat_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/categories'), $filename);
            $category->image = 'uploads/categories/' . $filename;
        }

        $category->save();

        return redirect()->route('categories.index')->with('success', 'Kategori güncellendi.');
    }

    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        // İlişkili ürün kontrolü (Opsiyonel: Silmeyi engellemek istersen)
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Bu kategoriye ait ürünler var! Önce ürünleri silin veya taşıyın.');
        }

        if ($category->image && File::exists(public_path($category->image))) {
            File::delete(public_path($category->image));
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori silindi.');
    }
}
