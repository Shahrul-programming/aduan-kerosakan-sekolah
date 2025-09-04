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

        \App\Models\User::query()->delete();

        // Super Admin
        \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@demo.com',
            'role' => 'super_admin',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        // School Admin
        \App\Models\User::create([
            'name' => 'School Admin',
            'email' => 'schooladmin@demo.com',
            'role' => 'school_admin',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        // Contractor
        \App\Models\User::create([
            'name' => 'Contractor Demo',
            'email' => 'contractor@demo.com',
            'role' => 'contractor',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        // Technician
        \App\Models\User::create([
            'name' => 'Technician Demo',
            'email' => 'technician@demo.com',
            'role' => 'technician',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        // Seed schools and complaints
        $this->call([
            SchoolSeeder::class,
            ComplaintSeeder::class,
        ]);
    }
}
