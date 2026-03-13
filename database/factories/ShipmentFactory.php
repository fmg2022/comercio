<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shipment>
 */
class ShipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $shippingDate = $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d');
        $randDay = rand(0, 3);

        return [
            'carrier_name' => $this->faker->company(),
            'tracking_number' => strtoupper($this->faker->bothify('???-####-??')),
            'shipping_cost' => $this->faker->randomFloat(2, 1000, 10000),
            'shipped_at' => $shippingDate,
            'delivered_at' => $randDay > 0 ? Carbon::parse($shippingDate)->addDays($randDay)->format('Y-m-d') : null,
        ];
    }
}
