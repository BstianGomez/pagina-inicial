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
                'name'     => 'Super Administrador',
                'email'    => 'superadmin@test.com',
                'password' => Hash::make('password'),
                'rol'      => 'super_admin',
            ],
            [
                'name'     => 'Administrador',
                'email'    => 'admin@test.com',
                'password' => Hash::make('password'),
                'rol'      => 'admin',
            ],
            [
                'name'     => 'Aprobador',
                'email'    => 'aprobador@test.com',
                'password' => Hash::make('password'),
                'rol'      => 'aprobador',
            ],
            [
                'name'     => 'Gestor',
                'email'    => 'gestor@test.com',
                'password' => Hash::make('password'),
                'rol'      => 'gestor',
            ],
            [
                'name'     => 'Usuario',
                'email'    => 'usuario@test.com',
                'password' => Hash::make('password'),
                'rol'      => 'usuario',
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );
        }
    }
}
