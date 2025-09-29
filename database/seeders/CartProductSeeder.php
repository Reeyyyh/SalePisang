<?php

namespace Database\Seeders;

use App\Models\CartProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $users = User::all();
        $products = Product::all();

        // Buat 30 item cart secara acak
        for ($i = 0; $i < 30; $i++) {
            CartProduct::factory()->create([
                'user_id' => $users->random()->id,
                'product_id' => $products->random()->id,
                'qty' => rand(1, 5), // Random qty antara 1-5
            ]);
        }
    }
}
