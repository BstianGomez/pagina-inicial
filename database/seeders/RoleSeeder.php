<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        Role::firstOrCreate(['name' => 'Superadmin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Gestor', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Aprobador', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Usuario', 'guard_name' => 'web']);
    }
}
