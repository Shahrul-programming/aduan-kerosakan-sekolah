<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$c = \App\Models\Complaint::find(5);
if (! $c) { echo "Complaint 5 not found\n"; exit(1); }
echo "Complaint 5 assigned_to=" . ($c->assigned_to ?? 'NULL') . " acknowledged_status=" . ($c->acknowledged_status ?? 'NULL') . " status=" . $c->status . "\n";
return 0;
