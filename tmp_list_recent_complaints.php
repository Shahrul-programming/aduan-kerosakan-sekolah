<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Complaint;

$complaints = Complaint::latest()->take(10)->get();
foreach ($complaints as $c) {
    echo "id={$c->id} school_id={$c->school_id} assigned_to=" . ($c->assigned_to ?? 'NULL') . " acknowledged_status=" . ($c->acknowledged_status ?? 'NULL') . " status={$c->status}\n";
}
