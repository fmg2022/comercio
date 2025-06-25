<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => 0,
            'date' => now(),
            'type' => fake()->randomElement(['tarjeta credito', 'tarjeta debito', 'transferencia', 'billetera virtual']),
            'code_transaction' => 'TRX-' . strtoupper(uniqid()),
            'order_id' => null,
            'payment_status_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
