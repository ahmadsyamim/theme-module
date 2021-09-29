<?php

namespace Modules\Theme\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetThemeFromSession
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
        //Check default theme
        if(session()->has('theme') && \Theme::exists(session('theme'))){
            \Theme::set(session('theme'));
        } else if (\Theme::exists('default')) {
            \Theme::set('default');
        }
        return $next($request);
    }
}
