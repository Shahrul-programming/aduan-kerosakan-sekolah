<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\School;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = [
            [
                'name' => 'SMK Bandar Utama',
                'address' => 'Jalan Bandar Utama 1, 47800 Petaling Jaya, Selangor',
                'phone' => '03-7725-1234',
                'email' => 'admin@smkbandarutama.edu.my',
                'ppd' => 'PPD Petaling Jaya',
                'code' => 'SMKBU',
                'principal_name' => 'En. Ahmad',
                'principal_phone' => '012-3456789',
                'hem_name' => 'Pn. Siti',
                'hem_phone' => '013-9876543',
                'qr_code' => null,
            ],
            [
                'name' => 'SMK Taman Tun Dr. Ismail',
                'address' => 'Jalan Burhanuddin Helmi, 60000 Kuala Lumpur',
                'phone' => '03-7728-5678',
                'email' => 'admin@smkttdi.edu.my',
                'ppd' => 'PPD Kuala Lumpur',
                'code' => 'SMKTTDI',
                'principal_name' => 'En. Rahim',
                'principal_phone' => '012-1112222',
                'hem_name' => 'Pn. Salmah',
                'hem_phone' => '013-3334444',
                'qr_code' => null,
            ],
            [
                'name' => 'SMK Subang Jaya',
                'address' => 'Jalan SS 14/4, 47500 Subang Jaya, Selangor',
                'phone' => '03-5634-9012',
                'email' => 'admin@smksubangjaya.edu.my',
                'ppd' => 'PPD Subang Jaya',
                'code' => 'SMKSJ',
                'principal_name' => 'En. Zulkifli',
                'principal_phone' => '012-2223333',
                'hem_name' => 'Pn. Noraini',
                'hem_phone' => '013-4445555',
                'qr_code' => null,
            ],
            [
                'name' => 'SMK Shah Alam',
                'address' => 'Seksyen 7, 40000 Shah Alam, Selangor',
                'phone' => '03-5511-3456',
                'email' => 'admin@smkshahalam.edu.my',
                'ppd' => 'PPD Shah Alam',
                'code' => 'SMKSA',
                'principal_name' => 'En. Faizal',
                'principal_phone' => '012-3334444',
                'hem_name' => 'Pn. Mariam',
                'hem_phone' => '013-5556666',
                'qr_code' => null,
            ],
            [
                'name' => 'SMK Klang',
                'address' => 'Jalan Tengku Kelana, 41000 Klang, Selangor',
                'phone' => '03-3371-7890',
                'email' => 'admin@smkklang.edu.my',
                'ppd' => 'PPD Klang',
                'code' => 'SMKKLANG',
                'principal_name' => 'En. Hafiz',
                'principal_phone' => '012-4445555',
                'hem_name' => 'Pn. Zainab',
                'hem_phone' => '013-6667777',
                'qr_code' => null,
            ]
        ];

        foreach ($schools as $school) {
            School::create($school);
        }
    }
}
