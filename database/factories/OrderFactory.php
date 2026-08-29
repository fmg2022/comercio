<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

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
    public function definition(): array
    {
        $user_id = DB::table('users')->inRandomOrder()->value('id');
        $state_id = DB::table('order_states')->inRandomOrder()->value('id');

        return [
            'date' => $this->faker->dateTimeBetween('-12 months', 'now')->format('Y-m-d H:i:s'),
            'total' => 0,
            'iva' => 0,
            'shipping_cost' => 0,
            'notes' => $this->faker->optional(0.3)->sentence,
            'user_id' => $user_id,
            'order_state_id' => $state_id,
        ];
    }
}
