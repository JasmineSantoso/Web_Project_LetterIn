<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class NormalUserSeeder extends Seeder
{
    /**
     * Seed some regular (non‑admin) users.
     * Uses firstOrCreate so existing rows are left untouched.
     */
    public function run(): void
    {
        $users = [
            [
                'fullname' => 'John Doe',
                'username' => 'johndoe',
                'email'    => 'john@example.com',
                'password' => Hash::make('Password123!'),
                'is_admin'=> false,
            ],
            [
                'fullname' => 'Jane Smith',
                'username' => 'janesmith',
                'email'    => 'jane@example.com',
                'password' => Hash::make('Password123!'),
                'is_admin'=> false,
            ],
        ];

        foreach ($users as $data) {
            User::firstOrCreate(
                ['email' => $data['email'], 'username' => $data['username']],
                $data
            );
        }
    }
}
