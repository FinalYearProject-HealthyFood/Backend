<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $numberOfUsers = 3; // Specify the desired number of users

        for ($i = 0; $i < $numberOfUsers; $i++) {
            User::create([
                'name' => 'HFS Admin 0' . ($i + 1),
                'email' => 'admin0' . ($i + 1) . '@gmail.com',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(), // Mark email as verified
                'role_id' => 5, // Mark email as verified
            ]);
        }
        for ($i = 0; $i < $numberOfUsers; $i++) {
            User::create([
                'name' => 'HFS Manager 0' . ($i + 1),
                'email' => 'manager0' . ($i + 1) . '@gmail.com',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(), // Mark email as verified
                'role_id' => 4, // Mark email as verified
            ]);
        }
        for ($i = 0; $i < $numberOfUsers; $i++) {
            User::create([
                'name' => 'HFS Food Moder 0' . ($i + 1),
                'email' => 'foodmod0' . ($i + 1) . '@gmail.com',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(), // Mark email as verified
                'role_id' => 3, // Mark email as verified
            ]);
        }
        for ($i = 0; $i < $numberOfUsers; $i++) {
            User::create([
                'name' => 'HFS Order Moder 0' . ($i + 1),
                'email' => 'ordermod0' . ($i + 1) . '@gmail.com',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(), // Mark email as verified
                'role_id' => 2, // Mark email as verified
            ]);
        }
    }
}
