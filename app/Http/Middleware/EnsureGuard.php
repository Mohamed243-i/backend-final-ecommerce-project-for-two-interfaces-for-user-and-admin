<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
  
    
        public function handle($request, Closure $next, $guard)
        {
            if (Auth::guard($guard)->check()) {
                return $next($request);
            }
    
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
    
}


// use App\Http\Middleware\EnsureGuard;

// Route::get('/protected', [ProtectedController::class, 'index'])
//     ->middleware(EnsureGuard::class . ':api');

