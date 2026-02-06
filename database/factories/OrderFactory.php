<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $user_id = DB::table('users')->inRandomOrder()->first()->id;
        $state_id = DB::table('order_states')->inRandomOrder()->first()->id;
        $address_id = DB::table('addresses')->where('user_id', $user_id)->inRandomOrder()->first()->id;

        return [
            'date' => $this->faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            'total' => 0,
            'user_id' => $user_id,
            'order_state_id' => $state_id,
            'address_id' => $address_id,
        ];
    }
}
