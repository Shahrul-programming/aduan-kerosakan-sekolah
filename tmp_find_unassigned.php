<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Complaint;

$c = Complaint::whereNull('assigned_to')->where('status','<>','selesai')->first();
if (! $c) {
    echo "No unassigned complaint found.\n";
    exit(0);
}

echo "Found complaint id={$c->id} school_id={$c->school_id} status={$c->status} acknowledged_status=" . ($c->acknowledged_status ?? 'NULL') . "\n";
