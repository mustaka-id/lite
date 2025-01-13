<?php

namespace Database\Seeders;

use App\Filament\Admission\Pages\Register;
use App\Models\Admission\Registrant;
use App\Models\Admission\RegistrantPayment;
use App\Models\Admission\Wave;
use App\Models\Employee;
use App\Models\Student;
use App\Models\User;
use App\Models\Year;
use Illuminate\Database\Seeder;

class MasayaSeeder extends Seeder
{
    public function run(): void
    {
        $year = Year::create(['name' => 2025]);

        foreach (range(1, 2) as $i) {
            $year->periods()->create([
                'name' => "Semester {$i}",
            ]);
        }

        $wave = $year->waves()->create([
            'year_id' => $year->id,
            'name' => "PSB {$year->name}/" . ($year->name + 1),
            'opened_at' => now(),
            'closed_at' => now()->addMonth(10),
            'meta' => [
                'payment_components' => $this->getPaymentComponents(),
                'files' => $this->getAdmissionFiles(),
            ],
        ]);
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
                'category' => 'registrant',
                'name' => 'Raport',
                'required' => false,
            ],
        ];
    }

    private function getPaymentComponents(): array
    {
        return [
            ["category" => "Pesantren", "name" => "Pendaftaran", "amount" => 150000],
            ["category" => "Pesantren", "name" => "Administrasi Pesantren", "amount" => 100000],
            ["category" => "Pesantren", "name" => "Kitab kuning Madrasah Diniyah", "amount" => 170000],
            ["category" => "Pesantren", "name" => "Syahriah Pesantren", "amount" => 210000],
            ["category" => "Pesantren", "name" => "SPP Madrasah al-Qur’an", "amount" => 50000],
            ["category" => "Pesantren", "name" => "SPP Madrasah Diniyah", "amount" => 50000],
            ["category" => "Pesantren", "name" => "Makan 3x/hari/bulan", "amount" => 500000],
            ["category" => "Pesantren", "name" => "Pemakaian almari (menjadi hak milik)", "amount" => 350000],
            ["category" => "Pesantren", "name" => "Pemakaian kasur (menjadi hak milik)", "amount" => 400000],
            ["category" => "Pesantren", "name" => "Dana pengembangan Pesantren", "amount" => 500000],
            ["category" => "Madrasah", "name" => "Seragam olahraga", "amount" => 200000],
            ["category" => "Madrasah", "name" => "Seragam identitas", "amount" => 360000],
            ["category" => "Madrasah", "name" => "Pengembangan & inovasi Madrasah/tahun", "amount" => 1000000],
            ["category" => "Madrasah", "name" => "Assesment, project, dan praktikum/semester", "amount" => 300000],
            ["category" => "Madrasah", "name" => "SPP Madrasah/bulan", "amount" => 250000],
            ["category" => "Madrasah", "name" => "Laptop flip & touchscreen (uang muka)*", "amount" => 2500000],
            ["category" => "Madrasah", "name" => "Field trip/semester", "amount" => 2000000]
        ];
    }
}
