<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->guard('web')->check()){
            return $next($request);
        } else {
            auth()->guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            abort(403, 'Sorry! can not access this page. You are Forbidden');
        }
    }
}
