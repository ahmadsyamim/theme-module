<?php

namespace Modules\Theme\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Igaster\LaravelTheme\Facades\Theme as LaravelTheme;
use Modules\Theme\Entities\Theme;

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
        $theme = Theme::where('default',1)->get();
        if ($theme->count() && LaravelTheme::exists($theme->first()->title)) {
            LaravelTheme::set($theme->first()->title);
        } else if (LaravelTheme::exists('default')) {
            if (!Theme::where('default', 1)->count()) {
                Theme::where('title', 'default')->update(['default' => 1]);
            }
            LaravelTheme::set('default');
        }
        return $next($request);
    }
}
