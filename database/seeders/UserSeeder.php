<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create super admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@padelgo.com',
            'phone' => '+1234567890',
            'password' => Hash::make('password123'),
            'role' => UserRole::SUPER_ADMIN,
            'latitude' => 40.7128,
            'longitude' => -74.0060,
        ]);

        // Create court admin
        User::create([
            'name' => 'Court Admin',
            'email' => 'court@padelgo.com',
            'phone' => '+1234567891',
            'password' => Hash::make('password123'),
            'role' => UserRole::COURT_ADMIN,
            'latitude' => 40.7180,
            'longitude' => -74.0020,
        ]);

        // Create regular users
        User::factory(10)->create([
            'role' => UserRole::USER,
        ]);
    }
}
