<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentStatus>
 */
class PaymentStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ambil user yang sudah ada, atau buat baru jika kosong
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        return [
            'user_id' => $user->id,  // User yang sama dengan yang akan digunakan Order
            'payment_method_id' => PaymentMethod::inRandomOrder()->first()->id ?? PaymentMethod::factory(),
            'status' => $this->faker->randomElement(['pending', 'paid', 'failed']),
        ];
    }
}
