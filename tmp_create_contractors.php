<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use App\Models\School;
use App\Models\Contractor;

// Prefer school id 12 if it exists, otherwise pick the first school or create a demo one.
$school = School::find(12) ?: School::first();
if (! $school) {
    $school = School::create([
        'name' => 'Sekolah Demo',
        'code' => 'DEM001',
        'address' => 'Alamat Sekolah Demo',
        'phone' => '03-00000000',
        'email' => 'demo@sekolah.test',
    ]);
    echo "Created demo school with id: {$school->id}\n";
}

$schoolId = $school->id;

$c1 = Contractor::create([
    'name' => 'Kontraktor A',
    'company_name' => 'Kontraktor Sdn Bhd',
    'phone' => '0123456789',
    'email' => 'kontraktorA@example.test',
    'address' => 'Alamat',
    'school_id' => $schoolId,
]);

$c2 = Contractor::create([
    'name' => 'Kontraktor B',
    'company_name' => 'Kontraktor Bhd',
    'phone' => '0198765432',
    'email' => 'kontraktorB@example.test',
    'address' => 'Alamat 2',
    'school_id' => $schoolId,
]);

echo "Created contractors: {$c1->id}, {$c2->id}\n";

