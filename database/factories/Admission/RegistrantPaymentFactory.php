<?php

namespace Database\Factories\Admission;

use App\Enums\PaymentMethod;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrantPaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => fake()->numerify('TRX-##########'),
            'name' => fake()->randomElement(['DP', 'Pelunasan']),
            'method' => fake()->randomElement(PaymentMethod::class),
            'paid_at' => fake()->dateTimeBetween('-7 days', 'now'),
            'receiver_id' => User::first()?->id,
        ];
    }
}
