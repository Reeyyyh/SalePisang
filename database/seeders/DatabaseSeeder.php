<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,            // 1. Buat User lebih dulu
            PaymentMethodSeeder::class,   // 2. Payment Method harus ada dulu sebelum Payment Status
            PaymentStatusSeeder::class,   // 3. Payment Status dibuat setelah User dan Payment Method
            CategorySeeder::class,        // 4. Kategori dibuat sebelum Produk
            ProductSeeder::class,         // 5. Produk dibuat setelah Kategori
            OrderSeeder::class,           // 6. Order dibuat setelah User dan Payment Status
            CartProductSeeder::class,     // 7. Cart dibuat terakhir karena butuh User & Produk
        ]);
    }
}
