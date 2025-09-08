<?php
require __DIR__ . '/vendor/autoload.php';
// Boot the framework
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$u = User::updateOrCreate(
    ['email' => 'superadmin@demo.com'],
    ['name' => 'Super Admin', 'password' => Hash::make('AdminPass123!'), 'role' => 'super_admin']
);
echo "CREATED_USER_ID:" . $u->id . "\n";
