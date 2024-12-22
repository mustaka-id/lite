<?php

namespace Database\Seeders;

use App\Filament\Admission\Pages\Register;
use App\Models\Admission\Registrant;
use App\Models\Admission\RegistrantBillItem;
use App\Models\Admission\RegistrantPayment;
use App\Models\Admission\Wave;
use App\Models\Employee;
use App\Models\Student;
use App\Models\User;
use App\Models\Year;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Arr;

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
                    'payment_components' => $this->getPaymentComponents(),
                    'files' => $this->getAdmissionFiles(),
                ],
            ]);
        }

        $firstRegistrant = User::first()?->registrants()->create([
            'wave_id' => Wave::first()->id,
            'registered_at' => now()
        ]);

        collect([$firstRegistrant])->merge(Registrant::factory(25)->create())->each(function ($registrant) {
            $bill = Register::assignBills($registrant);
            Register::assignFiles($registrant);

            if ($registrant->id % 2 === 0) {
                $amount = $bill->items()->sum('amount');
                RegistrantPayment::factory()->create([
                    'registrant_id' => $registrant->id,
                    'bill_id' => $bill->id,
                    'payer_id' => $registrant->user_id,
                    'amount' => fake()->randomElement([$amount / 2, $amount]),
                ]);
            }
        });
    }

    private function getAdmissionFiles(): array
    {
        return [
            [
                'category' => 'user',
                'name' => 'Kartu Keluarga',
                'required' => true,
            ],
            [
                'category' => 'user',
                'name' => 'KTP',
                'required' => true,
            ],
        ];
    }

    private function getPaymentComponents(): array
    {
        return [
            [
                'category' => 'Pesantren',
                'name' => 'Biaya Pendaftaran',
                'amount' => fake()->numberBetween(5, 100) * 10000,
            ],
            [
                'category' => 'Pesantren',
                'name' => 'Uang Gedung',
                'amount' => fake()->numberBetween(5, 100) * 10000,
            ],
            [
                'category' => 'Madrasah',
                'name' => 'Biaya Ujian',
                'amount' => fake()->numberBetween(5, 100) * 10000,
            ],
            [
                'category' => 'Madrasah',
                'name' => 'Osis',
                'amount' => fake()->numberBetween(5, 100) * 10000,
            ],
        ];
    }
}
