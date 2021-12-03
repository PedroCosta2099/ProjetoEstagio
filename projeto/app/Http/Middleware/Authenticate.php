<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
     /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
      

        if (Auth::guard('customer')->guest())
        {
            if ($request->expectsJson()) {
                return response()->json([
                    'error'   => 'unauthenticated',
                    'message' => 'Your authentication on API is wrong or has failed.',
                    'hint'    => 'Check if your bearer access token is valid or generate a new bearer access token.'
                ], 401);
            } elseif ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest(route('customer.login')); // <--- note this
            }
        }

        return $next($request);
    }
}
