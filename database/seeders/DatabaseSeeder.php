<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

       $checkAdminExists = User::query()->where('email', 'admin@example.com')->exists();
        // Check if admin user already exists
        if (! $checkAdminExists)
        {
            // Create admin user if not already existing
            User::factory()->create([
                'name' => 'admin',
                'email' => 'admin@example.com',
            ]);
        }
    }
}
