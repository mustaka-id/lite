<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => $user = User::factory(),
            'name' => $user?->name ?? fake()->name(),
            'code' => fake()->numerify('S-######'),
            'joined_at' => fake()->dateTimeBetween('-5 years', 'now'),
        ];
    }
}
