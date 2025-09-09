<?php

// Bootstrap Laravel and send 3 test notification emails (assignment, acknowledge, completion)
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Mail\ComplaintNotification;
use App\Models\Complaint;
use Illuminate\Support\Facades\Mail;

$c = Complaint::first();
if (! $c) {
    echo "No complaints found in DB.\n";
    exit(1);
}

try {
    Mail::to('kopiais5671@gmail.com')->send(new ComplaintNotification($c, 'assignment', 'Anda telah ditugaskan untuk aduan ini dari sekolah '.($c->school->name ?? 'N/A')));
    Mail::to('kopiais5671@gmail.com')->send(new ComplaintNotification($c, 'acknowledge', 'Kontraktor telah menerima tugasan untuk aduan ini.'));
    Mail::to('kopiais5671@gmail.com')->send(new ComplaintNotification($c, 'completion', 'Aduan ini telah selesai diselesaikan.'));
    echo "3 test emails sent (assignment, acknowledge, completion)\n";
} catch (\Exception $e) {
    echo 'Error sending emails: '.$e->getMessage()."\n";
}
