<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Drainase & Saluran Air', 'description' => 'Saluran tersumbat, banjir, genangan air, dan kerusakan drainase.'],
            ['name' => 'Infrastruktur & Jalan', 'description' => 'Jalan berlubang, trotoar rusak, jembatan, dan fasilitas umum.'],
            ['name' => 'Kebersihan & Sampah', 'description' => 'Tumpukan sampah, TPS penuh, dan masalah kebersihan lingkungan.'],
            ['name' => 'Penerangan Jalan', 'description' => 'Lampu jalan mati, rusak, atau area gelap yang rawan.'],
        ];

        $categoryIds = [];
        foreach ($categories as $category) {
            $categoryIds[$category['name']] = Category::updateOrCreate(
                ['name' => $category['name']],
                $category
            )->id;
        }

        $accounts = [
            ['name' => 'Admin Kelurahan', 'email' => 'admin@sigap.id', 'role' => User::ROLE_ADMIN, 'phone' => '081200000001', 'category_id' => null],
            ['name' => 'Warga Contoh', 'email' => 'warga@sigap.id', 'role' => User::ROLE_USER, 'phone' => '081200000002', 'category_id' => null],
            ['name' => 'Petugas Drainase', 'email' => 'petugas.drainase@sigap.id', 'role' => User::ROLE_PETUGAS, 'phone' => '081200000011', 'category_id' => $categoryIds['Drainase & Saluran Air']],
            ['name' => 'Petugas Infrastruktur', 'email' => 'petugas.jalan@sigap.id', 'role' => User::ROLE_PETUGAS, 'phone' => '081200000012', 'category_id' => $categoryIds['Infrastruktur & Jalan']],
            ['name' => 'Petugas Kebersihan', 'email' => 'petugas.kebersihan@sigap.id', 'role' => User::ROLE_PETUGAS, 'phone' => '081200000013', 'category_id' => $categoryIds['Kebersihan & Sampah']],
            ['name' => 'Petugas Penerangan', 'email' => 'petugas.lampu@sigap.id', 'role' => User::ROLE_PETUGAS, 'phone' => '081200000014', 'category_id' => $categoryIds['Penerangan Jalan']],
        ];

        foreach ($accounts as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                $account + ['password' => Hash::make('password123')]
            );
        }
    }
}
