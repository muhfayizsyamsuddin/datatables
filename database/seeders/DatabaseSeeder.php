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
     *
     * Order matters:
     * 1. CategorySeeder — populates categories table (required by ProductSeeder FK).
     * 2. ProductSeeder  — resolves category_id from categories; safe to rerun.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name'  => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
