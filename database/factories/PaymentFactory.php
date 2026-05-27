<?php

namespace Database\Factories;

use App\Models\PaymentProvider;
use App\Models\PaymentState;
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
            'method' => $this->faker->randomElement(['Tarjeta crédito', 'Tarjeta débito', 'Transferencia bancaria', 'Cuenta corriente']),
            'nro_fee' => 1,
            'amount' => $this->faker->randomFloat(2, 1000, 10000),
            'paid_at' => null,
            'payment_state_id' => PaymentState::inRandomOrder()->value('id'),
            'payment_provider_id' => PaymentProvider::pluck('id')->random(),
            'transaction_id' => $provTransactionId,
            'paymentId' => 'pay_' . $provTransactionId,
            'provider_state' => $this->faker->randomElement($externalStates),
            'checkout_url' => "https://paymentprovider.com/checkout/{$provTransactionId}",
        ];
    }

    public function forCanceledOrder(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'payment_state_id' => PaymentState::where('code', 'CANCELADO')->value('id'),
                'provider_state' => 'failed',
                'checkout_url' => null,
                'paid_at' => null,
            ];
        });
    }
}
