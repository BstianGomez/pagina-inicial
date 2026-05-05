<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignRolesToUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // Determine role based on user name or properties
            if (str_contains(strtolower($user->name), 'admin total')) {
                $user->assignRole('Superadmin');
            } elseif (str_contains(strtolower($user->name), 'admin')) {
                $user->assignRole('Admin');
            } else {
                // Default role for other users
                $user->assignRole('Usuario');
            }
        }
    }
}
