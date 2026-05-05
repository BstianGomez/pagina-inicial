<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = \App\Models\User::with('roles')->get();
foreach ($users as $user) {
    $roles = $user->roles->pluck('name')->join(', ') ?: 'Sin roles';
    echo $user->name . ': ' . $roles . PHP_EOL;
}
