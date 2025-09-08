<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Contractor;
use Illuminate\Support\Facades\DB;

$updated = 0;
$contractors = Contractor::whereNull('school_id')->get();
foreach ($contractors as $c) {
    $pivot = DB::table('contractor_school')->where('contractor_id', $c->id)->first();
    if ($pivot && $pivot->school_id) {
        echo "Setting contractor id={$c->id} ({$c->name}) school_id={$pivot->school_id}\n";
        $c->school_id = $pivot->school_id;
        $c->save();
        $updated++;
    } else {
        echo "No pivot found for contractor id={$c->id} ({$c->name}), skipping\n";
    }
}

echo "Updated: {$updated}\n";
