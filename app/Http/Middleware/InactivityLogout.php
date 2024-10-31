<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class InactivityLogout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set inactivity timeout (in seconds)
        $timeout = 120; // 2 minutes

        // Check if the user is logged in
        if (Auth::guard('student')->check()) {
            // Get the last activity timestamp
            $lastActivity = Session::get('lastActivity');

            // If the last activity time is set, check if it exceeds the timeout
            if ($lastActivity && (time() - $lastActivity) > $timeout) {
                // Log out the user
                session()->flush();
                Session::forget('lastActivity');
                Auth::guard('student')->logout();
                return redirect('/'); // Redirect to login or wherever you want
            }

            // Update the last activity time
            Session::put('lastActivity', time());
        }

        return $next($request);
    }
}
