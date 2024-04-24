<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            $user = $request->user();

            if ($user && $user->type === 'admin') {
                return route('admin.pages');
            } elseif ($user && $user->type === 'doctor') {
                return route('doctor.pages');
            } else {
                return route('patient.pages');
            }
        }

        return route('login');
    }
}
