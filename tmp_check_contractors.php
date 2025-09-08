<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\School;
use App\Models\Contractor;
use App\Models\Complaint;
use Illuminate\Support\Facades\DB;

$needle = 'Desamin';
$school = School::where('name', 'like', "%{$needle}%")->first();
if (! $school) {
    echo "School not found for needle: {$needle}\n";
    exit(1);
}

echo "Found school: {$school->id} - {$school->name}\n\n";

$contractors_direct = Contractor::where('school_id', $school->id)->orderBy('name')->get();
echo "Contractors with direct school_id ({$contractors_direct->count()}):\n";
foreach ($contractors_direct as $c) {
    echo "- id={$c->id} name={$c->name} company={$c->company_name} email={$c->email} school_id={$c->school_id}\n";
}

echo "\nContractor pivot rows (contractor_school) linking to this school:\n";
$pivot = DB::table('contractor_school')->where('school_id', $school->id)->get();
foreach ($pivot as $p) {
    echo "- contractor_id={$p->contractor_id} school_id={$p->school_id}\n";
}

echo "\nContractors found via pivot/schools relation:\n";
$contractors_via_pivot = Contractor::whereHas('schools', function($q) use ($school){ $q->where('schools.id', $school->id); })->orderBy('name')->get();
foreach ($contractors_via_pivot as $c) {
    echo "- id={$c->id} name={$c->name} company={$c->company_name} email={$c->email} school_id={$c->school_id}\n";
}

$complaints = Complaint::where('school_id', $school->id)->get();
echo "\nComplaints for this school (count=".count($complaints).") showing assigned_to values:\n";
foreach ($complaints as $comp) {
    echo "- complaint_id={$comp->id} assigned_to={$comp->assigned_to} acknowledged_status={$comp->acknowledged_status}\n";
}

echo "\nDone.\n";
