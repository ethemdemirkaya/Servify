<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Ana Yemek Kategorisini bul
        $foodCat = Category::where('slug', 'ana-yemekler')->first();
        if($foodCat) {
            Product::create([
                'category_id' => $foodCat->id,
                'name' => 'Izgara Köfte',
                'description' => 'Özel baharatlarla hazırlanmış ızgara köfte.',
                'price' => 250.00,
                'is_active' => true,
            ]);
            Product::create([
                'category_id' => $foodCat->id,
                'name' => 'Tavuk Şiş',
                'description' => 'Marine edilmiş tavuk parçaları.',
                'price' => 180.00,
                'is_active' => true,
            ]);
        }

        // İçecek Kategorisini bul
        $drinkCat = Category::where('slug', 'icecekler')->first();
        if($drinkCat) {
            Product::create([
                'category_id' => $drinkCat->id,
                'name' => 'Ayran',
                'description' => 'Ev yapımı yayık ayranı.',
                'price' => 30.00,
                'is_active' => true,
            ]);
            Product::create([
                'category_id' => $drinkCat->id,
                'name' => 'Kola',
                'description' => 'Kutu kola.',
                'price' => 50.00,
                'is_active' => true,
            ]);
        }
    }
}
