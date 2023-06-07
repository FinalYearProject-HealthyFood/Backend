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
        $numberOfUsers = 5; // Specify the desired number of users

        for ($i = 0; $i < $numberOfUsers; $i++) {
            User::create([
                'name' => 'Admin0' . ($i + 1),
                'email' => 'admin0' . ($i + 1) . '@gmail.com',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(), // Mark email as verified
            ]);
        }
    }
}
