<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$email = 'superadmin_oc@test.com';
$user = User::updateOrCreate(
    ['email' => $email],
    [
        'name' => 'Super Admin OC',
        'password' => Hash::make('password'),
        'role' => 'Superadmin',
        'rol' => 'Superadmin',
        'assigned_app' => 'oc',
    ]
);

$user->syncRoles(['Superadmin']);

echo "User created: " . $user->email . "\n";
