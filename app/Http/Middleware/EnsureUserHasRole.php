<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'This account is deactivated. Contact an administrator.',
            ]);
        }

        if ($user->role !== $role) {
            $destination = match ($user->role) {
                'admin' => 'admin.dashboard',
                'user' => 'users.dashboard',
                default => null,
            };

            if ($destination === null) {
                abort(403);
            }

            return redirect()->route($destination);
        }

        return $next($request);
    }
}
