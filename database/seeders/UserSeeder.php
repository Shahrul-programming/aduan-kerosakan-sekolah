<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);
        User::create([
            'name' => 'School Admin',
            'email' => 'schooladmin@demo.com',
            'password' => Hash::make('password'),
            'role' => 'school_admin',
        ]);
        User::create([
            'name' => 'Contractor Demo',
            'email' => 'contractor@demo.com',
            'password' => Hash::make('password'),
            'role' => 'contractor',
        ]);
        User::create([
            'name' => 'Technician Demo',
            'email' => 'technician@demo.com',
            'password' => Hash::make('password'),
            'role' => 'technician',
        ]);
    }
}
