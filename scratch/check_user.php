<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::where('email', 'admin@portal.com')->first();
if ($user) {
    echo "User: " . $user->email . "\n";
    echo "isSuperAdmin: " . ($user->isSuperAdmin() ? 'YES' : 'NO') . "\n";
    echo "isAdmin: " . ($user->isAdmin() ? 'YES' : 'NO') . "\n";
    echo "Role Column: " . $user->role . "\n";
    echo "Rol Column: " . $user->rol . "\n";
    echo "Spatie Roles: " . $user->roles->pluck('name')->implode(', ') . "\n";
    echo "Assigned Apps: " . json_encode($user->assigned_apps) . "\n";
} else {
    echo "User not found\n";
}
