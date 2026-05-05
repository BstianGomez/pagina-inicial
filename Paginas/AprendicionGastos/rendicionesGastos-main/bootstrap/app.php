<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthorizationException $e, Request $request) {
            if ($request->expectsJson()) {
                return null;
            }

            $message = 'No tiene permisos para realizar esta accion.';
            if ($request->is('gastos/crear') || $request->is('gastos/crear/*')) {
                $message = 'No tiene permisos para enviar una solicitud.';
            }

            $previousUrl = url()->previous();
            if (!empty($previousUrl) && $previousUrl !== $request->fullUrl()) {
                return redirect()->to($previousUrl)->with('error', $message);
            }

            $fallbackRoute = auth()->check() ? 'dashboard' : 'login';
            return redirect()->route($fallbackRoute)->with('error', $message);
        });

        $exceptions->render(function (HttpExceptionInterface $e, Request $request) {
            if ($e->getStatusCode() !== 403 || $request->expectsJson()) {
                return null;
            }

            $message = 'No tiene permisos para realizar esta accion.';
            if ($request->is('gastos/crear') || $request->is('gastos/crear/*')) {
                $message = 'No tiene permisos para enviar una solicitud.';
            }

            $previousUrl = url()->previous();
            if (!empty($previousUrl) && $previousUrl !== $request->fullUrl()) {
                return redirect()->to($previousUrl)->with('error', $message);
            }

            $fallbackRoute = auth()->check() ? 'dashboard' : 'login';
            return redirect()->route($fallbackRoute)->with('error', $message);
        });
    })->create();
