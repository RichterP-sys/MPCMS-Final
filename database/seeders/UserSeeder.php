<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Member;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin account
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin1234'),
            ]
        );

        // Create member account
        Member::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'member_id' => 'M' . date('Ymd') . '0001',
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => 'user@gmail.com',
                'password' => Hash::make('user1234'),
                'phone' => '1234567890',
                'address' => '123 Test Street, Test City',
                'join_date' => now(),
                'status' => 'active'
            ]
        );
    }
}
