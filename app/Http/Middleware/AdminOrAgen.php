<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOrAgen
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('admin.login');
        }
        if (! in_array($user->role, ['admin', 'super_admin', 'superadmin', 'agen']) || ! $user->is_active) {
            abort(403, 'Unauthorized.');
        }
        return $next($request);
    }
}