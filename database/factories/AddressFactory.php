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
            'name' => $this->faker->randomElement(['Casa', 'Oficina', 'Departamento', 'Local', 'Otro']),
            'street' => $this->faker->streetName(),
            'intersection' => $this->faker->optional()->streetName(), // 50% de probabilidad de ser nulo
            'locality' => $this->faker->city(),
            'province' => $this->faker->state(),
            'postal_code' => $this->faker->postcode(),
            'latitude' => $this->faker->latitude(-24.77738, -24.67713),
            'longitude' => $this->faker->longitude(-65.44865, -65.38531),
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
