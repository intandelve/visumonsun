<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || $request->user()->role !== 'admin') {
            // Redirect ke halaman user biasa atau abort 403
            abort(403, 'Unauthorized.  Admin access only.');
        }

        return $next($request);
    }
}