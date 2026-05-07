<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UnifiedUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Usuario OC',
                'email' => 'oc@test.com',
                'password' => Hash::make('password'),
                'role' => 'Admin',
                'assigned_apps' => ['oc'],
            ],
            [
                'name' => 'Usuario Viajes',
                'email' => 'viajes@test.com',
                'password' => Hash::make('password'),
                'role' => 'Admin',
                'assigned_apps' => ['viajes'],
            ],
            [
                'name' => 'Usuario Rendicion',
                'email' => 'rendicion@test.com',
                'password' => Hash::make('password'),
                'role' => 'Admin',
                'assigned_apps' => ['rendicion'],
            ],
            [
                'name' => 'Bastián Gómez',
                'email' => 'bastian@test.com',
                'password' => Hash::make('password'),
                'role' => 'Superadmin',
                'assigned_apps' => ['oc', 'viajes', 'rendicion'],
            ]
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);
            
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'role' => $role,
                    'rol' => $role,
                    'assigned_app' => $userData['assigned_apps'][0] ?? null,
                ])
            );

            $user->syncRoles([$role]);
        }
    }
}
