<?php

namespace Database\Factories;

use App\Models\Shipping;
use App\Models\ShippingRate;
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
        $shippingRate = ShippingRate::inRandomOrder()->first(['id', 'cost', 'min_distance', 'max_distance']);

        return [
            'tracking_number' => $this->faker->bothify('TRK-####-????'),
            'cost' => $shippingRate->cost,
            'shipping_rate_id' => $shippingRate->id,
            'distance_km' => $this->faker->randomFloat(1, $shippingRate->min_distance, $shippingRate->max_distance),
            'estimated_delivery_date' => $this->faker->dateTimeBetween('-12 months', 'now')->format('Y-m-d H:i:s'),
            'delivered_to' => null,
            'notes' => $this->faker->optional()->sentence,
        ];
    }
}
