<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Offer>
 */
class OfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 months', '+1 week');
        $endDate = Carbon::parse($startDate)->addDays(rand(5, 21));
        $type_state = 'ACTIVA';

        $startDate > now()
            ? $type_state = 'BORRADOR'
            : ($endDate > now() ?: $type_state = 'EXPIRADA');

        return [
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate,
            'offer_state_id' => DB::table('offer_states')->where('code', $type_state)->first()->id,
            'offer_template_id' => DB::table('offer_templates')->inRandomOrder()->first()->id,
        ];
    }
}
