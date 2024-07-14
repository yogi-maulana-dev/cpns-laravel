<?php

namespace App\Http\Middleware;

use Closure;

class EnsureUserHasRole
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $userRole = auth()->user()->role;

        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        $route = '';
        // return abort(403);

        return redirect()->route('dashboard.index')
            ->with('failed', 'Kamu tidak memilik izin untuk mengakses halaman tersebut.');
    }
}
