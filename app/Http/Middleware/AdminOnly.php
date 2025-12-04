<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // kalau belum login
        if (! $user) {
            return redirect()->route('admin.login');
        }

        // sesuaikan dengan kolom role di sys_user: admin / superadmin
        if (! in_array($user->role, ['admin', 'super_admin', 'superadmin'])) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
