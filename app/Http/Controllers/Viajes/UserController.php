<?php

namespace App\Http\Controllers\Viajes;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Roles que pueden gestionar usuarios
    private const ROLES_ADMIN = ['super_admin', 'admin'];

    private function rolActual(): string
    {
        return Auth::user()->rol ?? 'usuario';
    }

    private function puedeGestionarUsuarios(): bool
    {
        return in_array($this->rolActual(), self::ROLES_ADMIN);
    }

    public function index()
    {
        if (! $this->puedeGestionarUsuarios()) {
            abort(403, 'No tienes permiso para ver esta sección.');
        }
        $users = User::whereJsonContains('assigned_apps', 'viajes')->get();
        return view('viajes.usuarios', compact('users'));
    }

    public function store(Request $request)
    {
        if (! $this->puedeGestionarUsuarios()) {
            abort(403);
        }

        $rolesPermitidos = $this->rolActual() === 'super_admin'
            ? ['super_admin', 'admin', 'aprobador', 'gestor', 'usuario']
            : ['admin', 'aprobador', 'gestor', 'usuario']; // admin no puede crear super_admin

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|string|min:8|confirmed',
            'rol'           => 'required|in:' . implode(',', $rolesPermitidos),
            'assigned_apps' => 'required|array|min:1',
            'assigned_apps.*' => 'required|in:oc,viajes,rendicion',
        ]);

        $assignedApps = array_values(array_unique($request->input('assigned_apps', ['viajes'])));

        User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'rol'           => $request->rol,
            'assigned_app'  => $assignedApps[0] ?? null,
            'assigned_apps' => $assignedApps,
        ]);

        return redirect()->route('viajes.usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, User $user)
    {
        if (! $this->puedeGestionarUsuarios()) {
            abort(403);
        }

        // Admin no puede modificar a super_admin
        if ($this->rolActual() === 'admin' && $user->rol === 'super_admin') {
            return redirect()->route('viajes.usuarios.index')->with('error', 'No tienes permiso para modificar a un Super Administrador.');
        }

        $rolesPermitidos = $this->rolActual() === 'super_admin'
            ? ['super_admin', 'admin', 'aprobador', 'gestor', 'usuario']
            : ['admin', 'aprobador', 'gestor', 'usuario'];

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'rol'           => 'required|in:' . implode(',', $rolesPermitidos),
            'assigned_apps' => 'required|array|min:1',
            'assigned_apps.*' => 'required|in:oc,viajes,rendicion',
        ]);

        $assignedApps = array_values(array_unique($request->input('assigned_apps', ['viajes'])));
        $data = [
            'name'          => $request->name,
            'email'         => $request->email,
            'rol'           => $request->rol,
            'assigned_app'  => $assignedApps[0] ?? null,
            'assigned_apps' => $assignedApps,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('viajes.usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        if (! $this->puedeGestionarUsuarios()) {
            abort(403);
        }

        // Admin no puede eliminar a super_admin
        if ($this->rolActual() === 'admin' && $user->rol === 'super_admin') {
            return redirect()->route('viajes.usuarios.index')->with('error', 'No tienes permiso para eliminar a un Super Administrador.');
        }

        $user->delete();
        return redirect()->route('viajes.usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
