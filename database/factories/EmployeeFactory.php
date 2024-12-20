<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => $user = User::factory(),
            'name' => $user?->name ?? fake()->name(),
            'code' => fake()->numberBetween(100000, 999999),
            'joined_at' => fake()->dateTimeBetween('-5 years', 'now'),
        ];
    }
}
