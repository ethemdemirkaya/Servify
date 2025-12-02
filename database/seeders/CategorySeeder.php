<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::create([
            'name' => 'Ana Yemekler',
            'slug' => 'ana-yemekler',
            'image' => 'main-dishes.jpg'
        ]);

        Category::create([
            'name' => 'İçecekler',
            'slug' => 'icecekler',
            'image' => 'drinks.jpg'
        ]);

        Category::create([
            'name' => 'Tatlılar',
            'slug' => 'tatlilar',
            'image' => 'desserts.jpg'
        ]);
    }
}
