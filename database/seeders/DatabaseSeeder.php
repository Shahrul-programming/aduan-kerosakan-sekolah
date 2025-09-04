<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Super Admin
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@demo.com',
            'role' => 'super_admin',
            'password' => bcrypt('password123'),
        ]);

        // School Admin
        User::factory()->create([
            'name' => 'School Admin',
            'email' => 'schooladmin@demo.com',
            'role' => 'school_admin',
            'password' => bcrypt('password123'),
        ]);

        // Contractor
        User::factory()->create([
            'name' => 'Contractor Demo',
            'email' => 'contractor@demo.com',
            'role' => 'contractor',
            'password' => bcrypt('password123'),
        ]);

        // Technician
        User::factory()->create([
            'name' => 'Technician Demo',
            'email' => 'technician@demo.com',
            'role' => 'technician',
            'password' => bcrypt('password123'),
        ]);
    }
}
