<?php
/**
 * Author: Jun Chen
 */

namespace Database\Factories;

use App\Models\User;
use App\Models\Winner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Winner>
 */
class WinnerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'points' => fake()->numberBetween(1, 20),
            'won_at' => fake()->dateTimeBetween('-1 year'),
        ];

    }
}
