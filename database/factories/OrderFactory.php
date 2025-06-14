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
        $status_id = DB::table('statuses')->inRandomOrder()->value('id');

        return [
            'date' => $this->faker->dateTimeBetween('-5 years')->format('Y-m-d'),
            'total' => 0,
            'user_id' => $user_id,
            'status_id' => $status_id,
            'offer_id' => null,
        ];
    }
}
