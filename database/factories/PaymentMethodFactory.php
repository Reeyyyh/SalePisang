<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentMethod>
 */
class PaymentMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $usedCodes = [];

        $methods = [
            ['name' => 'Credit Card', 'provider' => 'Visa', 'code' => 'credit_card'],
            ['name' => 'Bank Transfer', 'provider' => 'BCA', 'code' => 'bca_va'],
            ['name' => 'Bank Transfer', 'provider' => 'BNI', 'code' => 'bni_va'],
            ['name' => 'E-Wallet', 'provider' => 'GoPay', 'code' => 'gopay'],
            ['name' => 'E-Wallet', 'provider' => 'ShopeePay', 'code' => 'shopeepay'],
        ];

        // Ambil metode yang belum dipakai
        $availableMethods = array_filter($methods, fn ($m) => !in_array($m['code'], $usedCodes));

        if (empty($availableMethods)) {
            return [
                'name' => 'Other',
                'provider' => 'Unknown',
                'code' => 'other_' . uniqid(),
            ];
        }

        $selected = $this->faker->randomElement($availableMethods);
        $usedCodes[] = $selected['code'];

        return $selected;
    }
}
