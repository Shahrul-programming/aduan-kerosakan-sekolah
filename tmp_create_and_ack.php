<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Contractor;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

echo "Create and acknowledge test script\n";

$contractor = Contractor::whereNotNull('user_id')
    ->where(function($q) {
        $q->whereNotNull('school_id')->orWhereHas('schools');
    })->first();

if (! $contractor) {
    echo "No contractor with user_id and school found.\n";
    exit(1);
}

$schoolId = $contractor->school_id ?: ($contractor->schools()->first()->id ?? null);
if (! $schoolId) {
    echo "Contractor has no school id.\n";
    exit(1);
}

echo "Using contractor id={$contractor->id} user_id={$contractor->user_id} school_id={$schoolId}\n";

$complaint = Complaint::create([
    'title' => 'TMP Create and Ack',
    'school_id' => $schoolId,
    'category' => 'ujian',
    'description' => 'Temporary complaint for ack test',
    'priority' => 'sederhana',
    'complaint_number' => 'TMPACK-' . time(),
    'user_id' => 1,
    'reported_by' => 1,
    'status' => 'baru',
    'reported_at' => now(),
]);

echo "Created complaint id={$complaint->id} assigned_to=" . ($complaint->assigned_to ?? 'NULL') . " acknowledged_status=" . ($complaint->acknowledged_status ?? 'NULL') . "\n";

// Simulate login as contractor user
Auth::loginUsingId($contractor->user_id);
echo "Logged in as user id={$contractor->user_id}\n";

$controller = new \App\Http\Controllers\ComplaintController();
$request = Request::create('/ack', 'POST', ['acknowledge' => 'accepted']);

try {
    $controller->acknowledge($request, $complaint->id);
    echo "Controller acknowledge executed.\n";
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}

$complaint->refresh();

echo "After: complaint id={$complaint->id} assigned_to={$complaint->assigned_to} acknowledged_status={$complaint->acknowledged_status} status={$complaint->status}\n";

// cleanup
$complaint->delete();

echo "Temporary complaint deleted. Done.\n";

return 0;
