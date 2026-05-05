<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnifiedUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Usuario OC',
            'email' => 'oc@test.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'assigned_app' => 'oc',
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'name' => 'Usuario Viajes',
            'email' => 'viajes@test.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'assigned_app' => 'viajes',
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'name' => 'Usuario Rendicion',
            'email' => 'rendicion@test.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'assigned_app' => 'rendicion',
            'role' => 'admin',
        ]);
    }
}
