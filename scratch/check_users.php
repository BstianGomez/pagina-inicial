<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$email = 'admin@example.com'; // Change if known, otherwise list all admins
$admins = User::all()->filter(function($u) {
    return $u->isAdmin() || $u->isSuperAdmin() || $u->isAprobador();
});

foreach ($admins as $u) {
    echo "ID: {$u->id}, Name: {$u->name}, Email: {$u->email}, Role: {$u->role}, Rol: {$u->rol}\n";
    echo "isAprobador: " . ($u->isAprobador() ? 'YES' : 'NO') . "\n";
    echo "-------------------\n";
}
