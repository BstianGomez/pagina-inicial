<?php

namespace App\Http\Controllers\OC;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::whereJsonContains('assigned_apps', 'oc')
            ->orderBy('name')
            ->paginate(15);

        return view('oc.users.index', compact('users'));
    }

    public function create()
    {
        return view('oc.users.create');
    }

    public function store(Request $request)
    {
        $currentUser = auth()->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\'-]+$/u'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:super_admin,admin,gestor,cliente'],
            'assigned_apps' => ['required', 'array', 'min:1'],
            'assigned_apps.*' => ['required', Rule::in(['oc', 'viajes', 'rendicion'])],
        ]);

        if ($currentUser->hasRole('gestor')) {
            if ($data['role'] !== 'cliente') {
                abort(403, 'El gestor solo puede crear clientes.');
            }
        }

        if ($data['role'] === 'super_admin' && ! $currentUser->isSuperAdmin()) {
            abort(403, 'No tienes permiso para crear super admin.');
        }

        if ($data['role'] === 'admin' && ! $currentUser->isSuperAdmin() && ! $currentUser->isAdmin()) {
            abort(403, 'No tienes permiso para crear admin.');
        }

        if ($data['role'] === 'admin' && $currentUser->hasRole('admin')) {
            // admin puede crear admin o gestor/cliente, pero no super admin
            // permitido
        }

        $assignedApps = array_values(array_unique($data['assigned_apps']));

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'rol' => $data['role'],
            'assigned_app' => $assignedApps[0] ?? null,
            'assigned_apps' => $assignedApps,
        ]);

        return redirect()->route('oc.users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        $currentUser = auth()->user();
        if ($currentUser->hasRole('gestor')) {
            abort(403, 'No tienes permiso para editar usuarios.');
        }

        // Super admin no puede editar otros super admin
        if ($currentUser->isSuperAdmin() && $user->isSuperAdmin() && $user->id !== $currentUser->id) {
            abort(403, 'No puedes editar otros super admin.');
        }

        // Admin no puede editar super admin ni admin
        if ($currentUser->isAdmin() && ! $currentUser->isSuperAdmin()) {
            if ($user->isSuperAdmin() || ($user->isAdmin() && $user->id !== $currentUser->id)) {
                abort(403, 'No tienes permiso para editar este usuario.');
            }
        }

        if ($user->isSuperAdmin() && ! $currentUser->isSuperAdmin()) {
            abort(403, 'No tienes permiso para editar este usuario.');
        }

        return view('oc.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $currentUser = auth()->user();

        if ($currentUser->hasRole('gestor')) {
            abort(403, 'No tienes permiso para editar usuarios.');
        }

        // Super admin no puede editar otros super admin
        if ($currentUser->isSuperAdmin() && $user->isSuperAdmin() && $user->id !== $currentUser->id) {
            abort(403, 'No puedes editar otros super admin.');
        }

        // Admin no puede editar super admin ni admin
        if ($currentUser->isAdmin() && ! $currentUser->isSuperAdmin()) {
            if ($user->isSuperAdmin() || ($user->isAdmin() && $user->id !== $currentUser->id)) {
                abort(403, 'No tienes permiso para editar este usuario.');
            }
        }

        if ($user->isSuperAdmin() && ! $currentUser->isSuperAdmin()) {
            abort(403, 'No tienes permiso para editar este usuario.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\'-]+$/u'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:super_admin,admin,gestor,cliente'],
            'assigned_apps' => ['required', 'array', 'min:1'],
            'assigned_apps.*' => ['required', Rule::in(['oc', 'viajes', 'rendicion'])],
        ]);

        if ($data['role'] === 'super_admin' && ! $currentUser->isSuperAdmin()) {
            abort(403, 'No tienes permiso para asignar super admin.');
        }

        if ($currentUser->hasRole('admin') && $data['role'] === 'super_admin') {
            abort(403, 'No tienes permiso para asignar super admin.');
        }

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'rol' => $data['role'],
        ];

        if (! empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $assignedApps = array_values(array_unique($data['assigned_apps']));
        $payload['assigned_apps'] = $assignedApps;
        $payload['assigned_app'] = $assignedApps[0] ?? null;

        $user->update($payload);

        return redirect()->route('oc.users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        $currentUser = auth()->user();

        if ($user->id === $currentUser->id) {
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        // Gestor solo puede eliminar usuarios normales
        if ($currentUser->hasRole('gestor')) {
            if ($user->role !== 'cliente') {
                abort(403, 'Solo puedes eliminar usuarios normales.');
            }
        }

        // Super admin no puede eliminar otros super admin
        if ($currentUser->isSuperAdmin() && $user->isSuperAdmin()) {
            abort(403, 'No puedes eliminar otros super admin.');
        }

        // Admin no puede eliminar super admin ni admin
        if ($currentUser->isAdmin() && ! $currentUser->isSuperAdmin()) {
            if ($user->isSuperAdmin() || $user->isAdmin()) {
                abort(403, 'No tienes permiso para eliminar este usuario.');
            }
        }

        if ($user->isSuperAdmin() && ! $currentUser->isSuperAdmin()) {
            abort(403, 'No tienes permiso para eliminar este usuario.');
        }

        $user->delete();

        return redirect()->route('oc.users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
