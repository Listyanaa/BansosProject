<?php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        if (!$user || !in_array($user->role, $roles, true)) {
            abort(403, 'Khusus '.implode('/', $roles));
        }
        return $next($request);
    }
}
