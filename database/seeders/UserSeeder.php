<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
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
            'name' => 'Pengurusan Demo',
            'email' => 'pengurusan@demo.com',
            'password' => Hash::make('password'),
            'role' => 'pengurusan',
        ]);
        User::create([
            'name' => 'Guru Demo',
            'email' => 'guru@demo.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);
        User::create([
            'name' => 'Kontraktor Demo',
            'email' => 'kontraktor@demo.com',
            'password' => Hash::make('password'),
            'role' => 'kontraktor',
        ]);
    }
}
