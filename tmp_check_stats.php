<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$id = 3;
$stats = [
    'assigned_complaints' => App\Models\Complaint::where('assigned_to', $id)->count(),
    'pending_complaints' => App\Models\Complaint::where('assigned_to', $id)->where('status','pending')->count(),
    'in_progress_complaints' => App\Models\Complaint::where('assigned_to', $id)->where('status','in_progress')->count(),
    'completed_complaints' => App\Models\Complaint::where('assigned_to', $id)->where('status','completed')->count(),
    'recent_complaints' => App\Models\Complaint::where('assigned_to', $id)->with(['school','user'])->latest()->take(5)->get()
];
print_r(array_keys($stats));
echo "\n";
