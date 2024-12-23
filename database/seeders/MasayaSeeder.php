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
                'category' => 'user',
                'name' => 'KTP',
                'required' => true,
            ],
        ];
    }

    private function getPaymentComponents(): array
    {
        return [
            ["category" => "Pesantren", "name" => "Pendaftaran", "amount" => 100000],
            ["category" => "Pesantren", "name" => "Kitab Kuning/tahun", "amount" => 150000],
            ["category" => "Pesantren", "name" => "Penggunaan Listrik, Air, dan Syahriyah/Bulan", "amount" => 210000],
            ["category" => "Pesantren", "name" => "Makan 3x/ Hari/Bulan", "amount" => 500000],
            ["category" => "Pesantren", "name" => "Dana Pengembangan Pesantren (1x selama menjadi santri)", "amount" => 500000],
            ["category" => "Pesantren", "name" => "Biaya Kasur", "amount" => 450000],
            ["category" => "Pesantren", "name" => "Biaya pemakaian almari 3 tahun", "amount" => 350000],
            ["category" => "Pesantren", "name" => "SPP Madrasah Diniyah/bulan", "amount" => 50000],
            ["category" => "Madrasah", "name" => "Seragam Olahraga", "amount" => 200000],
            ["category" => "Madrasah", "name" => "Seragan Identitas", "amount" => 360000],
            ["category" => "Madrasah", "name" => "Pengembangan & Inovasi Madrasah/Tahun", "amount" => 1000000],
            ["category" => "Madrasah", "name" => "SPP Madrasah/Bulan", "amount" => 250000],
            ["category" => "Madrasah", "name" => "Laptop Flip & Touchscreen (Uang muka)*", "amount" => 2500000],
            ["category" => "Madrasah", "name" => "Study Lapangan/semester", "amount" => 200000],
            ["category" => "Madrasah", "name" => "Assesment, Project, dan Praktikum/Semester", "amount" => 300000]
        ];
    }
}
