<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Casa', 'Oficina', 'Departamento', 'Otro']),
            'street_1' => $this->faker->streetAddress,
            'street_2' => $this->faker->optional()->secondaryAddress, // 50% de probabilidad de ser nulo
            'locality' => $this->faker->city,
            'province' => $this->faker->state,
            'postal_code' => $this->faker->postcode,
            'is_default' => false,
        ];
    }

    /**
     * Estado para indicar que esta dirección es la predeterminada.
     */
    public function default(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_default' => true,
        ]);
    }
}
