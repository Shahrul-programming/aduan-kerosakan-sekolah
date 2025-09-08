<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use App\Models\School;
use App\Models\User;
$schools = School::where('name','like','%Taman Universiti%')->get(['id','name','code']);
echo json_encode($schools->toArray(), JSON_PRETTY_PRINT);
foreach($schools as $s){
    $users = User::where('school_id',$s->id)->get(['id','name','email','role'])->toArray();
    echo "\n-- users for school {$s->id} --\n";
    echo json_encode($users, JSON_PRETTY_PRINT);
}
