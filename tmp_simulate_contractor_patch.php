<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Complaint;

$userid = 3; // contractor user id (mysatt@demo.com)
$complaintId = 4;

echo "Simulate contract user id={$userid} PATCH complaint id={$complaintId}\n";

$user = \App\Models\User::find($userid);
if (! $user) {
    echo "User id={$userid} not found\n";
    exit(1);
}

Auth::loginUsingId($userid);
echo "Logged in as user id=" . auth()->id() . " role=" . (auth()->user()->role ?? 'NULL') . "\n";

$complaint = Complaint::find($complaintId);
if (! $complaint) {
    echo "Complaint id={$complaintId} not found\n";
    exit(1);
}

echo "Before: assigned_to={$complaint->assigned_to} status={$complaint->status} acknowledged_status={$complaint->acknowledged_status}\n";

$request = Request::create('/fake', 'PATCH', ['status' => 'in_progress']);

$controller = new \App\Http\Controllers\ComplaintController();

try {
    $response = $controller->updateStatus($request, $complaint);
    echo "Controller returned: ";
    if (is_object($response) && method_exists($response, 'getContent')) {
        echo $response->getContent() . "\n";
    } else {
        var_export($response);
        echo "\n";
    }
} catch (\Exception $e) {
    echo "Controller threw exception: " . get_class($e) . " - " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

$complaint->refresh();

echo "After: assigned_to={$complaint->assigned_to} status={$complaint->status} acknowledged_status={$complaint->acknowledged_status}\n";

// cleanup: logout
Auth::logout();

echo "Done.\n";
