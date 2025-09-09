<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schoolAdmin = User::where('role', 'school_admin')->first();
        $schools = School::all();

        if (! $schoolAdmin || $schools->isEmpty()) {
            return; // Skip if no school admin or schools
        }

        $damages = [
            [
                'title' => 'Kerosakan Bumbung Dewan',
                'description' => 'Bumbung dewan bocor pada waktu hujan. Air masuk ke dalam dewan dan merosakkan kerusi dan peralatan.',
                'category' => 'struktur',
                'priority' => 'tinggi',
                'status' => 'pending',
            ],
            [
                'title' => 'Paip Air Pecah di Tandas',
                'description' => 'Paip air pecah di tandas murid tingkat 2. Air melimpah dan menyebabkan banjir kecil.',
                'category' => 'plumbing',
                'priority' => 'urgent',
                'status' => 'in_progress',
            ],
            [
                'title' => 'Lampu Rosak di Bilik Guru',
                'description' => 'Beberapa lampu fluorescent tidak berfungsi di bilik guru. Perlu penggantian.',
                'category' => 'elektrik',
                'priority' => 'sederhana',
                'status' => 'completed',
            ],
            [
                'title' => 'Tingkap Pecah Kelas 3A',
                'description' => 'Tingkap kelas 3A pecah akibat bola sepak. Kaca berserakan dan berbahaya.',
                'category' => 'struktur',
                'priority' => 'tinggi',
                'status' => 'pending',
            ],
            [
                'title' => 'Kipas Siling Rosak',
                'description' => 'Kipas siling di perpustakaan tidak berputar dan mengeluarkan bunyi bising.',
                'category' => 'elektrik',
                'priority' => 'sederhana',
                'status' => 'in_progress',
            ],
            [
                'title' => 'Lantai Retak di Koridor',
                'description' => 'Terdapat retakan besar pada lantai koridor utama. Berisiko menyebabkan kemalangan.',
                'category' => 'struktur',
                'priority' => 'tinggi',
                'status' => 'pending',
            ],
            [
                'title' => 'Pintu Almari Rosak',
                'description' => 'Pintu almari di makmal sains tidak dapat ditutup dengan sempurna.',
                'category' => 'furniture',
                'priority' => 'rendah',
                'status' => 'completed',
            ],
            [
                'title' => 'Sistem Pembunyi Tidak Berfungsi',
                'description' => 'Sistem pembunyi untuk pengumuman harian tidak berfungsi sejak seminggu lalu.',
                'category' => 'elektrik',
                'priority' => 'sederhana',
                'status' => 'in_progress',
            ],
        ];

        foreach ($damages as $index => $damage) {
            Complaint::create([
                'complaint_number' => 'ADU'.str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'title' => $damage['title'],
                'description' => $damage['description'],
                'category' => $damage['category'],
                'priority' => $damage['priority'],
                'status' => $damage['status'],
                'school_id' => $schools->random()->id,
                'user_id' => $schoolAdmin->id,
                'reported_by' => $schoolAdmin->id,
                'reported_at' => now()->subDays(rand(1, 30)),
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(0, 7)),
            ]);
        }
    }
}
