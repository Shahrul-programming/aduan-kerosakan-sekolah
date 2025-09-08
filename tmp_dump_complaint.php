<?php
// tmp_dump_complaint.php - temporary diagnostic script
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

$number = 'ADU-2025-09-0002';
$c = \App\Models\Complaint::where('complaint_number', $number)->with(['school','user'])->first();
if (! $c) {
    echo "COMPLAINT NOT FOUND\n";
    exit(0);
}

$out = [
    'id' => $c->id,
    'complaint_number' => $c->complaint_number,
    'school_id' => $c->school_id,
    'school_name' => $c->school ? $c->school->name : null,
    'reported_by' => $c->reported_by,
    'reporter_name' => $c->user ? $c->user->name : null,
    'description' => $c->description,
    'image' => $c->image,
    'video' => $c->video,
    'status' => $c->status,
    'acknowledged_status' => $c->acknowledged_status,
    'created_at' => $c->created_at ? $c->created_at->toDateTimeString() : null,
];

// Check storage existence and resolved URLs
if ($c->image) {
    $out['image_is_absolute'] = Str::startsWith($c->image, ['http://','https://']);
    $out['image_exists_public_disk'] = Storage::disk('public')->exists($c->image);
    $out['image_public_url'] = Storage::disk('public')->exists($c->image) ? Storage::disk('public')->url($c->image) : null;
    $out['image_storage_path'] = storage_path('app/public/' . ltrim($c->image, '/'));
    $out['image_file_exists_on_fs'] = file_exists($out['image_storage_path']);
}

if ($c->video) {
    $out['video_is_absolute'] = Str::startsWith($c->video, ['http://','https://']);
    $out['video_exists_public_disk'] = Storage::disk('public')->exists($c->video);
    $out['video_public_url'] = Storage::disk('public')->exists($c->video) ? Storage::disk('public')->url($c->video) : null;
    $out['video_storage_path'] = storage_path('app/public/' . ltrim($c->video, '/'));
    $out['video_file_exists_on_fs'] = file_exists($out['video_storage_path']);
}

echo json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
