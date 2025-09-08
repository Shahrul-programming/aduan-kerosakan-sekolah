<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Contractor;
use Illuminate\Support\Facades\DB;

$all = Contractor::orderBy('id')->get();
$byEmail = [];
$deleted = 0;
DB::beginTransaction();
foreach ($all as $c) {
    $email = strtolower(trim($c->email));
    if (!isset($byEmail[$email])) {
        $byEmail[$email] = $c; // keep the first (lowest id)
    } else {
        // delete duplicate
        $dup = $c;
        echo "Deleting duplicate contractor id={$dup->id} email={$dup->email}\n";
        $dup->delete();
        $deleted++;
    }
}
DB::commit();

echo "Deleted duplicates: $deleted\n";
