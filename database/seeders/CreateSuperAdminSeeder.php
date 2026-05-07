<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@portal.com'],
            [
                'name' => 'Administrador Global',
                'password' => Hash::make('admin1234'),
                'role' => 'Superadmin',
                'rol' => 'Superadmin',
                'assigned_app' => 'rendicion', // fallback legacy
                'assigned_apps' => ['oc', 'viajes', 'rendicion'],
            ]
        );
        $user->syncRoles(['Superadmin']);
    }
}
