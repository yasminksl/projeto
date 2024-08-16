<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'postal_code' => fake()->postcode(),
            'street_address' => fake()->streetAddress(),
            'address_number' => fake()->numberBetween(1, 9999),
            'city' => fake()->city(),
            'neighborhood' => fake()->word(),
        ];
    }
}
