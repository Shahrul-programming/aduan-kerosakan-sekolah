<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Complaint;
use App\Models\Contractor;
use App\Models\User;

$id = 4;
$complaint = Complaint::find($id);
if (! $complaint) {
    echo "Complaint id={$id} not found\n";
    exit(1);
}

echo "Complaint id={$complaint->id}\n";
echo "assigned_to: ";
var_export($complaint->assigned_to);
echo "\n";
echo "acknowledged_status: ";
var_export($complaint->acknowledged_status);
echo "\n";
echo "status: {$complaint->status}\n";
echo "school_id: {$complaint->school_id}\n";

if ($complaint->assigned_to) {
    $contractor = Contractor::find($complaint->assigned_to);
    if ($contractor) {
        echo "Contractor record (id={$contractor->id}): name={$contractor->name} user_id={$contractor->user_id} school_id={$contractor->school_id}\n";
        if ($contractor->user_id) {
            $user = User::find($contractor->user_id);
            if ($user) {
                echo "Linked user: id={$user->id} email={$user->email} role={$user->role}\n";
            } else {
                echo "Linked user_id={$contractor->user_id} not found in users table\n";
            }
        }
    } else {
        echo "No contractor found with id={$complaint->assigned_to}\n";
        // Maybe assigned_to stored user id historically
        $user = User::find($complaint->assigned_to);
        if ($user) {
            echo "But there is a user with id={$user->id} (email={$user->email}) - assigned_to might refer to user id historically\n";
        }
    }
} else {
    echo "assigned_to is NULL\n";
    echo "Finding contractors for school_id={$complaint->school_id}\n";
    $cs = Contractor::where('school_id', $complaint->school_id)->orWhereHas('schools', function($q) use ($complaint) {
        $q->where('schools.id', $complaint->school_id);
    })->get();
    echo "Contractors linked to this school: count=" . $cs->count() . "\n";
    foreach ($cs as $c) {
        echo "- id={$c->id} name={$c->name} user_id={$c->user_id}\n";
    }
}

return 0;
