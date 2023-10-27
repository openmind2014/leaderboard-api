<?php
/**
 * Author: Jun Chen
 */

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
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
            'age' => fake()->numberBetween(16, 55),
            'points' => fake()->numberBetween(1, 20),
            'address' => fake()->address(),
        ];
    }
}
