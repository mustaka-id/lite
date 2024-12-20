<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Year;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $year = Year::factory()->create([
            'name' => '2024',
        ]);

        $year->periods()->create([
            'name' => 'Semester 1',
        ]);

        $year->waves()->create([
            'year_id' => $year->id,
            'name' => 'Gelombang 1',
            'opened_at' => now(),
            'closed_at' => now()->addMonth(10),
            'meta' => [
                'payment_components' => [
                    [
                        'category' => 'Pesantren',
                        'name' => 'Biaya Pendaftaran',
                        'amount' => 100000,
                    ],
                    [
                        'category' => 'Pesantren',
                        'name' => 'Uang Gedung',
                        'amount' => 100000,
                    ],
                    [
                        'category' => 'Madrasah',
                        'name' => 'Biaya Ujian',
                        'amount' => 200000,
                    ],
                    [
                        'category' => 'Madrasah',
                        'name' => 'Osis',
                        'amount' => 50000,
                    ],
                ],
            ],
        ]);
    }
}
