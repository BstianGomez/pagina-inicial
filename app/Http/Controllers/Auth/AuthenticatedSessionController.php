<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        
        $apps = $user->assigned_apps ?? $user->assigned_app ?? [];
        if (is_string($apps)) {
            $decoded = json_decode($apps, true);
            $apps = is_array($decoded) ? $decoded : [$apps];
        }
        $apps = array_filter((array) $apps);
        
        $app = count($apps) > 1 ? null : ($apps[0] ?? null);

        if (!$app && count($apps) > 1) {
            $redirect = route('app-redirect', absolute: false);
        } else {
            $redirect = match($app) {
                'oc' => route('oc.index', absolute: false),
                'viajes' => route('viajes.mis-solicitudes', absolute: false),
                'rendicion' => route('rendicion.reports.index', absolute: false),
                default => route('dashboard', absolute: false),
            };
        }

        if (count($apps) === 1 && $app) {
            return redirect($redirect);
        }

        return redirect()->intended($redirect);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        // Verificar si el usuario tiene múltiples apps asignadas
        $apps = $user->assigned_apps ?? $user->assigned_app ?? [];
        if (is_string($apps)) {
            $decoded = json_decode($apps, true);
            $apps = is_array($decoded) ? $decoded : [$apps];
        }
        $apps = array_filter((array)$apps); // Remover valores vacíos

        // Si tiene más de una app, redirigir a selección en lugar de logout
        if (count($apps) > 1) {
            return redirect()->route('app-redirect');
        }

        // Si tiene una sola app o ninguna, hacer logout completo
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
