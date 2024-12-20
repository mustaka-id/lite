<?php

namespace Database\Factories\Admission;

use App\Models\Admission\Wave;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'wave_id' => ($wave = fake()->randomElement(Wave::all()))->id,
            'user_id' => User::factory(),
            'registered_by' => User::first()?->id,
            'registered_at' => fake()->dateTimeBetween($wave->opened_at, $wave->closed_at),
        ];
    }
}
