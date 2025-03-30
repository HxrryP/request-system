<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com', // Change this
            'password' => Hash::make('password'), // Change this securely!
            'role' => 'admin',
            'avatar' => 'path/to/avatar.jpg', // Optional: path to avatar
            'phone' => '123-456-7890', // Optional: phone number
            'address' => '123 Admin St, Admin City, Admin Country', // Optional: address
            'email_verified_at' => now(), // Optional: mark as verified
        ]);
    }
}
