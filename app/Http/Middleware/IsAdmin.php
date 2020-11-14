<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Redirect to the login if user is not authenticated.
        if (!$user) {
            return redirect('login');
        }

        // when a user is authenticated, but not an admin,
        // redirect back to the bookmarks list
        if (!$user->isAdmin()) {
            return redirect('bookmarks');
        }

        return $next($request);
    }
}
