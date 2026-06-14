<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@opalhaven.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '+1-555-0100',
            'is_active' => true,
        ]);

        // Create regular users
        $users = [
            ['name' => 'John Doe', 'email' => 'john@example.com'],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com'],
            ['name' => 'Michael Johnson', 'email' => 'michael@example.com'],
            ['name' => 'Sarah Williams', 'email' => 'sarah@example.com'],
            ['name' => 'Robert Brown', 'email' => 'robert@example.com'],
            ['name' => 'Emily Davis', 'email' => 'emily@example.com'],
            ['name' => 'David Wilson', 'email' => 'david@example.com'],
            ['name' => 'Lisa Anderson', 'email' => 'lisa@example.com'],
            ['name' => 'James Taylor', 'email' => 'james@example.com'],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password123'),
                'role' => 'user',
                'phone' => '+1-555-' . str_pad(rand(100, 999), 4, '0', STR_PAD_LEFT),
                'is_active' => true,
            ]);
        }
    }
}
