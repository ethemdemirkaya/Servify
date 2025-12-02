<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DiningTable;

class DiningTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            DiningTable::create([
                'name' => "Masa $i",
                'capacity' => 4,
                'status' => 'empty',
                'qr_code' => "table-$i-qr",
            ]);
        }
    }
}
