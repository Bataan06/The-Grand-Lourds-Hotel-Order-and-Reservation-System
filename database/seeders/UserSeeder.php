<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin Account
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@hotel.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Staff Account
        User::create([
            'name'     => 'Staff',
            'email'    => 'staff@hotel.com',
            'password' => Hash::make('password'),
            'role'     => 'staff',
        ]);

        // Regular User Account
        User::create([
            'name'     => 'John User',
            'email'    => 'user@hotel.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
        ]);
    }
}