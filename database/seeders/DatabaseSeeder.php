<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
        public function run(): void
        {
            // Admin User
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@admin.com',
                'password' => 'password', // will be hashed by model/factory
                'role' => 'admin',
            ]);

            // Regular User
            User::factory()->create([
                'name' => 'Regular User',
                'email' => 'user@user.com',
                'password' => 'password',
                'role' => 'user',
            ]);

            $this->call([
                RainfallDataSeeder::class,
                WindSpeedDataSeeder::class, 
                HistoricalRainfallSeeder::class,
                WindMapDataSeeder::class,
                ForecastSeeder::class,
            ]);
        }
}
