<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Usuario solo OC
        User::updateOrCreate(
            ['email' => 'oc@example.com'],
            [
                'name' => 'Usuario OC',
                'password' => bcrypt('password123'),
                'assigned_apps' => json_encode(['oc']),
            ]
        );

        // Usuario solo Viajes
        User::updateOrCreate(
            ['email' => 'viajes@example.com'],
            [
                'name' => 'Usuario Viajes',
                'password' => bcrypt('password123'),
                'assigned_apps' => json_encode(['viajes']),
            ]
        );

        // Usuario solo Rendición
        User::updateOrCreate(
            ['email' => 'rendicion@example.com'],
            [
                'name' => 'Usuario Rendición',
                'password' => bcrypt('password123'),
                'assigned_apps' => json_encode(['rendicion']),
            ]
        );

        // Usuario con acceso a OC y Viajes
        User::updateOrCreate(
            ['email' => 'multi1@example.com'],
            [
                'name' => 'Usuario MultiApp 1',
                'password' => bcrypt('password123'),
                'assigned_apps' => json_encode(['oc', 'viajes']),
            ]
        );

        // Usuario con acceso a las tres apps
        User::updateOrCreate(
            ['email' => 'multi2@example.com'],
            [
                'name' => 'Usuario MultiApp 2',
                'password' => bcrypt('password123'),
                'assigned_apps' => json_encode(['oc', 'viajes', 'rendicion']),
            ]
        );

        // Seeders de datos maestros
        $this->call([
            CategoryCecoSeeder::class,
            OcProjectSettingsSeeder::class,
            SampleDataSeeder::class,
        ]);
    }
}
