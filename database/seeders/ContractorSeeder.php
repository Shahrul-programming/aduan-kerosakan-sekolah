<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contractor;

class ContractorSeeder extends Seeder
{
    public function run()
    {
        $contractors = [
            [
                'name' => 'Kontraktor A',
                'company_name' => 'Kontraktor Sdn Bhd',
                'phone' => '0123456789',
                'email' => 'kontraktorA@example.test',
                'address' => 'Alamat',
                'school_id' => 12,
            ],
            [
                'name' => 'Kontraktor B',
                'company_name' => 'Kontraktor Bhd',
                'phone' => '0198765432',
                'email' => 'kontraktorB@example.test',
                'address' => 'Alamat 2',
                'school_id' => 12,
            ],
        ];

        foreach ($contractors as $c) {
            Contractor::updateOrCreate(['email' => $c['email']], $c);
        }
    }
}
