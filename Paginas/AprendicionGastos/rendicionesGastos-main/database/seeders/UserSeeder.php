<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@sofofa.cl',
                'role' => 'Superadmin',
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@sofofa.cl',
                'role' => 'Admin',
            ],
            [
                'name' => 'Solicitante User',
                'email' => 'solicitante@sofofa.cl',
                'role' => 'Solicitante',
            ],
            [
                'name' => 'Aprobador User',
                'email' => 'aprobador@sofofa.cl',
                'role' => 'Aprobador',
            ],
            [
                'name' => 'Gestor User',
                'email' => 'gestor@sofofa.cl',
                'role' => 'Gestor',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
            ]);

            $user->assignRole($userData['role']);
        }
    }
}
