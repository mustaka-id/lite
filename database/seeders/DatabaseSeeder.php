<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Year;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'roles' => [UserRole::SuperAdmin]
        ]);

        if (app()->isProduction())
            $this->call(MasayaSeeder::class);
        else
            $this->call(DummySeeder::class);

        Artisan::call('laravolt:indonesia:seed');
    }
}
