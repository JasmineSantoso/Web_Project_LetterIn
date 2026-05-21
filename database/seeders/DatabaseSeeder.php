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
        // User::factory(10)->create();

        User::factory()->create([
            'fullname' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Seed admin user
        User::factory()->create([
            'fullname' => 'Jasmine Aulia',
            'username' => 'adminjeje',
            'email' => 'jasmine123@gmail.com',
            'password' => bcrypt('adminjeje123'),
            'is_admin' => true,
        ]);
    }
}
