<?php

namespace App\Http\Middleware;
use Closure;
use Session;

class Lang
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
        if ($lang = Session::get('lang')) {
            \Lang::setlocale($lang);
        }
        
        return $next($request);
    }
}
