<?php

namespace Database\Factories;

use App\Models\Shipping;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Shipping>
 */
class ShippingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tracking_number' => $this->faker->bothify('TRK-####-????'),
            'shipping_cost' => $this->faker->randomFloat(2, 300, 3500),
            'delivered_at' => null,
            'notes' => $this->faker->optional()->sentence,
            'is_feasible' => $this->faker->boolean(90), // 90% factible
        ];
    }
}
