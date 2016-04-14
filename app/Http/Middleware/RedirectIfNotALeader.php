<?php

namespace App\Http\Middleware;

use Closure;
use \Auth;

class RedirectIfNotALeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::guest()) {
            if (!Auth::user()->isALeader()) {
                return redirect('/home');
            }
        } else {
            return redirect('/');
        }
        
        return $next($request);
    }
}
