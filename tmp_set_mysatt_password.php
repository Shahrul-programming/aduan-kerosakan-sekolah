<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$email = 'mysatt@demo.com';
$plain = 'password';
$user = User::where('email', $email)->first();
if (! $user) {
    echo "User with email {$email} not found\n";
    exit(1);
}
$user->password = Hash::make($plain);
$user->save();

echo "Password for {$email} updated to ('{$plain}')\n";
