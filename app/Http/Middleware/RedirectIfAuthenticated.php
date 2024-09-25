<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Redirect;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if (Auth()->user()->hasRole('super_admin')) {
                    return Redirect::to(getSuperAdminDashboardURL());
                } elseif (Auth()->user()->hasRole('admin')) {
                    return redirect()->intended(getAdminDashboardURL());
                } elseif (Auth()->user()->hasRole('client')) {
                    return redirect()->intended(getClientDashboardURL());
                }
            }
        }

        return $next($request);
    }
}
