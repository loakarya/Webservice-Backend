<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MasterUAC
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
        if ( auth()->user() != null or auth()->user()->acl == 2 )
        return $next($request);

        return response()->json([
            'error' => 'You are not an admin'
        ], 403);
    }
}
