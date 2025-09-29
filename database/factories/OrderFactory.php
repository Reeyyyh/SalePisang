<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\PaymentStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Order::class;

    public function definition(): array
    {
        // Ambil user dari database, atau buat baru jika belum ada
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        // Pastikan PaymentStatus dibuat untuk user yang sama
        $paymentStatus = PaymentStatus::inRandomOrder()->first();

        return [
            'user_id' => $user->id, // Pastikan order pakai user yang sama
            'payment_status_id' => $paymentStatus->id,
            'total_amount' => $this->faker->randomFloat(2, 50, 1000),
            'orders_status' => $this->faker->randomElement(['pending', 'processing', 'shipped', 'delivered', 'canceled']),
        ];
    }
}
