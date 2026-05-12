<?php

namespace App\Http\Controllers\Rendicion;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasAnyRole(['Superadmin', 'Super Admin', 'Admin'])) {
            abort(403);
        }

        $users = User::with('roles')
            ->whereJsonContains('assigned_apps', 'rendicion')
            ->paginate(10);
        $roles = auth()->user()->hasAnyRole(['Superadmin', 'Super Admin'])
            ? Role::all()
            : Role::whereNotIn('name', ['Superadmin', 'Super Admin', 'Admin'])->get();

        return view('rendicion.users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['Superadmin', 'Super Admin', 'Admin'])) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|exists:roles,name',
            'has_fixed_fund' => 'required|boolean',
            'fixed_fund_amount' => 'nullable|required_if:has_fixed_fund,1|numeric|gt:0',
        ]);

        if (!$this->canAssignRole(auth()->user(), $validated['role'])) {
            return back()->with('error', 'No tienes permisos para asignar ese rol.');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'rol' => $validated['role'],
            'assigned_app' => 'rendicion',
            'assigned_apps' => ['rendicion'],
            'has_fixed_fund' => (bool) $validated['has_fixed_fund'],
            'fixed_fund_amount' => (bool) $validated['has_fixed_fund'] ? $validated['fixed_fund_amount'] : 0,
        ]);

        $user->assignRole($validated['role']);

        return back()->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->hasAnyRole(['Superadmin', 'Super Admin', 'Admin'])) {
            abort(403);
        }

        if (!$this->canManageTarget(auth()->user(), $user)) {
            return back()->with('error', 'No tienes permisos para modificar este usuario.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'role' => 'required|exists:roles,name',
            'has_fixed_fund' => 'required|boolean',
            'fixed_fund_amount' => 'nullable|required_if:has_fixed_fund,1|numeric|gt:0',
        ]);

        if (!$this->canAssignRole(auth()->user(), $validated['role'])) {
            return back()->with('error', 'No tienes permisos para asignar ese rol.');
        }

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'rol' => $validated['role'],
            'has_fixed_fund' => (bool) $validated['has_fixed_fund'],
            'fixed_fund_amount' => (bool) $validated['has_fixed_fund'] ? $validated['fixed_fund_amount'] : 0,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        $user->syncRoles([$validated['role']]);

        return back()->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->hasAnyRole(['Superadmin', 'Super Admin', 'Admin'])) {
            return back()->with('error', 'No tienes permisos para eliminar usuarios.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminarte a ti mismo.');
        }

        if (!$this->canManageTarget(auth()->user(), $user)) {
            return back()->with('error', 'No tienes permisos para eliminar este usuario.');
        }

        $user->delete();
        return back()->with('success', 'Usuario eliminado.');
    }

    private function canManageTarget(User $actor, User $target): bool
    {
        if ($actor->hasAnyRole(['Superadmin', 'Super Admin'])) {
            return true;
        }

        if ($actor->hasRole('Admin')) {
            return !$target->hasAnyRole(['Superadmin', 'Super Admin', 'Admin']);
        }

        return false;
    }

    private function canAssignRole(User $actor, string $roleName): bool
    {
        if ($actor->hasAnyRole(['Superadmin', 'Super Admin'])) {
            return true;
        }

        if ($actor->hasRole('Admin')) {
            return !in_array($roleName, ['Superadmin', 'Super Admin', 'Admin'], true);
        }

        return false;
    }
}
