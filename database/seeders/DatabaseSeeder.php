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

        // Pengurusan (Admin)
        User::factory()->create([
            'name' => 'Pengurusan Demo',
            'email' => 'pengurusan@demo.com',
            'role' => 'pengurusan',
            'password' => bcrypt('password123'),
        ]);

        // Guru (User)
        User::factory()->create([
            'name' => 'Guru Demo',
            'email' => 'guru@demo.com',
            'role' => 'guru',
            'password' => bcrypt('password123'),
        ]);

        // Kontraktor
        User::factory()->create([
            'name' => 'Kontraktor Demo',
            'email' => 'kontraktor@demo.com',
            'role' => 'kontraktor',
            'password' => bcrypt('password123'),
        ]);
    }
}
