<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin Hesabı
        User::create([
            'name' => 'Yönetici',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'), // Şifre: password
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Garson Hesabı
        User::create([
            'name' => 'Garson Ali',
            'email' => 'garson@admin.com',
            'password' => Hash::make('password'),
            'role' => 'waiter',
            'is_active' => true,
        ]);

        // Şef Hesabı
        User::create([
            'name' => 'Şef Mehmet',
            'email' => 'sef@admin.com',
            'password' => Hash::make('password'),
            'role' => 'chef',
            'is_active' => true,
        ]);

        // Kasiyer Hesabı
        User::create([
            'name' => 'Kasiyer Ayşe',
            'email' => 'kasiyer@admin.com',
            'password' => Hash::make('password'),
            'role' => 'cashier',
            'is_active' => true,
        ]);
    }
}
