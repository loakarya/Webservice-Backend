<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminUAC
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
        if ( !auth()->check() )
        return response()->json([
            'status' => false,
            'message' => 'You are not logged in.'
        ], 401);
        
        if ( auth()->user() != null and ( auth()->user()->acl == 1 or auth()->user()->acl == 2 ))
            return $next($request);

        return response()->json([
            'error' => 'You are not an admin'
        ], 403);
    }
}
