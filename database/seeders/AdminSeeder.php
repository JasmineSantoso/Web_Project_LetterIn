<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the admin‑user seed.
     */
    public function run(): void
    {
        // Insert the admin only if it does not already exist.
        User::firstOrCreate(
            [
                'email'    => 'jasmine123@gmail.com',
                'username' => 'adminjeje',
            ],
            [
                'fullname' => 'Jasmine Aulia',
                'password' => Hash::make('adminjeje123'),
                'is_admin'=> true,
            ]
        );
    }
}
