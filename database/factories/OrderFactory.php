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
        $user = \App\Models\User::where('active', true)->inRandomOrder()->first();
        $state_id = DB::table('order_states')->inRandomOrder()->first()->id;

        return [
            'date' => $this->faker->dateTimeBetween('-12 months', 'now')->format('Y-m-d H:i:s'),
            'total' => 0,
            'iva' => 0,
            'address_id' => $user->addresses()->inRandomOrder()->first()->id,
            'user_id' => $user->id,
            'order_state_id' => $state_id,
        ];
    }
}
