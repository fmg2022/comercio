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
        $provTransactionId = $this->faker->uuid;
        $externalStates = ['pending', 'completed', 'failed', 'refunded'];
        return [
            'provider_transaction_id' => $provTransactionId,
            'provider_state' => $this->faker->randomElement($externalStates),
            'checkout_url' => "https://paymentprovider.com/checkout/{$provTransactionId}",
        ];
    }
}
