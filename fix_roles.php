<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

// Ensure Superadmin role exists
$role = Role::firstOrCreate(['name' => 'Superadmin', 'guard_name' => 'web']);

$emails = [
    'rendicion@example.com',
    'multi2@example.com',
    'oc@example.com',
    'viajes@example.com'
];

foreach ($emails as $email) {
    $user = User::where('email', $email)->first();
    if ($user) {
        if (!$user->hasRole('Superadmin')) {
            $user->assignRole('Superadmin');
            echo "Assigned Superadmin to $email\n";
        } else {
            echo "$email already has Superadmin role\n";
        }
        
        // Also ensure legacy role columns are set
        $user->update([
            'role' => 'super_admin',
            'rol' => 'super_admin',
        ]);
    } else {
        echo "User $email not found\n";
    }
}
