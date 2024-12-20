<?php

namespace Database\Seeders;

use App\Models\Admission\Registrant;
use App\Models\Employee;
use App\Models\Student;
use App\Models\Year;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DummySeeder extends Seeder
{
    public function run(): void
    {
        $year = Year::create(['name' => 2024]);

        Student::factory(25)->create();
        Employee::factory(25)->create();

        foreach (range(1, 2) as $i) {
            $year->periods()->create([
                'name' => "Semester {$i}",
            ]);
        }

        foreach (range(1, 2) as $i) {
            $wave = $year->waves()->create([
                'year_id' => $year->id,
                'name' => "Gelombang {$i}",
                'opened_at' => now(),
                'closed_at' => now()->addMonth(10),
                'meta' => [
                    'payment_components' => $this->getComponents(),
                ],
            ]);

            Registrant::factory(25)->create();
        }
    }

    private function getComponents(): array
    {
        return [
            [
                'category' => 'Pesantren',
                'name' => 'Biaya Pendaftaran',
                'amount' => fake()->numberBetween(5, 50) * 10000,
            ],
            [
                'category' => 'Pesantren',
                'name' => 'Uang Gedung',
                'amount' => fake()->numberBetween(5, 50) * 10000,
            ],
            [
                'category' => 'Madrasah',
                'name' => 'Biaya Ujian',
                'amount' => fake()->numberBetween(5, 50) * 10000,
            ],
            [
                'category' => 'Madrasah',
                'name' => 'Osis',
                'amount' => fake()->numberBetween(5, 50) * 10000,
            ],
        ];
    }
}
