<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Contractor;
use App\Models\Complaint;

$c = Complaint::find(5);
$scid = $c ? $c->school_id : null;
echo "Complaint 5 school_id=" . ($scid ?? 'NULL') . "\n";
$contractor = Contractor::find(1);
if (!$contractor) { echo "Contractor 1 not found\n"; exit(1);} 
echo "Contractor 1: id={$contractor->id} name={$contractor->name} school_id=" . ($contractor->school_id ?? 'NULL') . "\n";
$viaPivot = $contractor->schools()->pluck('schools.id')->toArray();
echo "Contractor->schools pivot ids: "; var_export($viaPivot); echo "\n";
return 0;
