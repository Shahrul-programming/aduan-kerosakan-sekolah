<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Complaint;
use App\Models\Contractor;

echo "Starting test acknowledge script\n";

$complaint = Complaint::whereNull('acknowledged_status')->whereNull('assigned_to')->first();
if (! $complaint) {
    echo "No complaint found with acknowledged_status NULL and assigned_to NULL. Trying any complaint with acknowledged_status NULL...\n";
    $complaint = Complaint::whereNull('acknowledged_status')->first();
}

if (! $complaint) {
    echo "No suitable complaint found to test. Exiting.\n";
    exit(0);
}

$school_id = $complaint->school_id;
echo "Found complaint id={$complaint->id} school_id={$school_id}\n";

$contractor = Contractor::where('school_id', $school_id)->first();
if (! $contractor) {
    $contractor = Contractor::whereHas('schools', function($q) use ($school_id) {
        $q->where('schools.id', $school_id);
    })->first();
}

if (! $contractor) {
    echo "No contractor associated with school_id={$school_id}. Exiting.\n";
    exit(0);
}

echo "Using contractor id={$contractor->id} name={$contractor->name}\n";

echo "Before: assigned_to=" . ($complaint->assigned_to ?? 'NULL') . " acknowledged_status=" . ($complaint->acknowledged_status ?? 'NULL') . " status={$complaint->status}\n";

$complaint->acknowledged_status = 'accepted';
$complaint->acknowledged_at = now();
$complaint->assigned_to = $contractor->id;
$complaint->status = 'assigned';
$complaint->save();

$complaint->refresh();

echo "After: assigned_to={$complaint->assigned_to} acknowledged_status={$complaint->acknowledged_status} status={$complaint->status}\n";

echo "Done.\n";
