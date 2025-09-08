<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Contractor;

$ids = [3,4];
$updated = 0;
foreach ($ids as $id) {
    $c = Contractor::find($id);
    if (!$c) {
        echo "Contractor id={$id} not found\n";
        continue;
    }
    echo "Updating contractor id={$id} name={$c->name} - setting school_id NULL (was: {$c->school_id})\n";
    $c->school_id = null;
    $c->save();
    $updated++;
}

echo "Total updated: {$updated}\n";
