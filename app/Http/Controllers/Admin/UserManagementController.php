<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $query = User::with('roles')
            ->where(function($q) {
                $q->whereHas('roles', function($rq) {
                    $rq->whereIn('name', ['Superadmin', 'Admin', 'Usuario']);
                })
                ->orWhereIn('rol', ['Superadmin', 'Admin', 'Usuario'])
                ->orWhereIn('role', ['Superadmin', 'Admin', 'Usuario']);
            })
            ->orderByRaw("CASE 
                WHEN rol = 'Superadmin' OR role = 'Superadmin' THEN 1 
                WHEN rol = 'Admin' OR role = 'Admin' THEN 2 
                WHEN rol = 'Usuario' OR role = 'Usuario' THEN 3 
                ELSE 4 END")
            ->orderBy('name');

        // Buscador por Correo
        if ($request->filled('search_email')) {
            $query->where('email', 'like', '%' . $request->search_email . '%');
        }

        // Filtro por Sistemas
        if ($request->filled('filter_app')) {
            $query->whereJsonContains('assigned_apps', $request->filter_app);
        }

        $users = $query->paginate(20)->withQueryString();
        
        // Roles disponibles para asignar globalmente (Orden jerárquico)
        $roles = Role::whereIn('name', ['Superadmin', 'Admin', 'Usuario'])
            ->orderByRaw("CASE 
                WHEN name = 'Superadmin' THEN 1 
                WHEN name = 'Admin' THEN 2 
                WHEN name = 'Usuario' THEN 3 
                ELSE 4 END")
            ->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $allowedRoles = ['Superadmin', 'Admin', 'Usuario'];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role_name' => ['required', Rule::in($allowedRoles)],
            'assigned_apps' => 'required|array|min:1',
            'assigned_apps.*' => 'required|in:oc,viajes,rendicion',
        ]);

        $assignedApps = array_values(array_unique($validated['assigned_apps']));

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role_name'],
            'rol' => $validated['role_name'],
            'assigned_app' => $assignedApps[0] ?? null,
            'assigned_apps' => $assignedApps,
        ]);

        $user->assignRole($validated['role_name']);

        return back()->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $allowedRoles = ['Superadmin', 'Admin', 'Usuario'];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_name' => ['required', Rule::in($allowedRoles)],
            'assigned_apps' => 'required|array|min:1',
            'assigned_apps.*' => 'required|in:oc,viajes,rendicion',
        ]);

        $assignedApps = array_values(array_unique($validated['assigned_apps']));

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role_name'],
            'rol' => $validated['role_name'],
            'assigned_app' => $assignedApps[0] ?? null,
            'assigned_apps' => $assignedApps,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles([$validated['role_name']]);

        return back()->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminarte a ti mismo.');
        }

        $user->delete();
        return back()->with('success', 'Usuario eliminado.');
    }
}
