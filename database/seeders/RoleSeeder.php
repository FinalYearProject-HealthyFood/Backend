<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'user']);
        Role::create(['name' => 'ordermod']);
        Role::create(['name' => 'foodmod']);
        Role::create(['name' => 'manager']);
        Role::create(['name' => 'admin']);
    }
}