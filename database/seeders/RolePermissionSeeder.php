<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'catalog.manage',
            'reports.create',
            'reports.update_own',
            'reports.delete_own',
            'reports.approve_manager',
            'reports.reject_manager',
            'reports.observe_manager',
            'reports.approve_reviewer',
            'reports.reject_reviewer',
            'reports.observe_reviewer',
            'reports.register_payment',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superadmin = Role::firstOrCreate(['name' => 'Superadmin']);
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $solicitante = Role::firstOrCreate(['name' => 'Solicitante']);
        $aprobador = Role::firstOrCreate(['name' => 'Gestor']);
        $gestor = Role::firstOrCreate(['name' => 'Aprobador']);

        $superadmin->syncPermissions($permissions);

        $admin->syncPermissions([
            'users.view',
            'users.create',
            'users.update',
            'catalog.manage',
            'reports.create',
            'reports.update_own',
        ]);

        $solicitante->syncPermissions([
            'reports.create',
            'reports.update_own',
            'reports.delete_own',
        ]);

        $aprobador->syncPermissions([
            'reports.approve_manager',
            'reports.reject_manager',
            'reports.observe_manager',
        ]);

        $gestor->syncPermissions([
            'reports.approve_reviewer',
            'reports.reject_reviewer',
            'reports.observe_reviewer',
            'reports.register_payment',
        ]);
    }
}
