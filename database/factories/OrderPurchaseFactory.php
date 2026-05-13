<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderPurchase>
 */
class OrderPurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $provider_id = DB::table('providers')->inRandomOrder()->first()->id;
        $state_id = DB::table('order_purchase_states')->inRandomOrder()->first()->id;
        return [
            'date' => $this->faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            'amount' => 0,
            'provider_id' => $provider_id,
            'order_purchase_states_id' => $state_id,
        ];
    }
}
