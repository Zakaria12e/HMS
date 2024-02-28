<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserTypeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $userType)
    {
        $allowedUserTypes = explode('|', $userType);

        if (auth()->check() && in_array(auth()->user()->type, $allowedUserTypes)) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
