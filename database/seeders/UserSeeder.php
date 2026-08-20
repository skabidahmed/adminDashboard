<?php

namespace Database\Seeders;

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
        // 1. Create Super Admin User
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Administrator',
                'password' => Hash::make('password'),
            ]
        );
        $superAdmin->assignRole('super_admin');

        // 2. Create Moderator User
        $moderator = User::firstOrCreate(
            ['email' => 'moderator@example.com'],
            [
                'first_name' => 'System',
                'last_name' => 'Moderator',
                'password' => Hash::make('password'),
            ]
        );
        $moderator->assignRole('moderator');

        // 3. Create Writer User
        $writer = User::firstOrCreate(
            ['email' => 'writer@example.com'],
            [
                'first_name' => 'Content',
                'last_name' => 'Writer',
                'password' => Hash::make('password'),
            ]
        );
        $writer->assignRole('writer');

        // 4. Create Standard User
        $standardUser = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'first_name' => 'Standard',
                'last_name' => 'User',
                'password' => Hash::make('password'),
            ]
        );
        $standardUser->assignRole('user');
    }
}
