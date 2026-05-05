<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'rendicion@example.com';
$user = \App\Models\User::where('email', $email)->first();

if (!$user) {
    \App\Models\User::create([
        'name' => 'Rendicion User',
        'email' => $email,
        'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        'role' => 'super_admin',
        'rol' => 'super_admin',
        'assigned_apps' => json_encode(['rendicion'])
    ]);
    echo "User created.\n";
} else {
    $user->update([
        'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        'role' => 'super_admin',
        'rol' => 'super_admin',
        'assigned_apps' => json_encode(['rendicion'])
    ]);
    echo "User updated.\n";
}
