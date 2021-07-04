<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class UserSettings
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
        if (Auth::user()) {

            if (Auth::user()->settings) {
                return $next($request);
            } else {
                //
                // dd($request);
                if (url('/register_settings') != $request->getUri() && url('/settings/save') != $request->getUri()) {
                    return redirect('/register_settings');
                }
            }
        }
        return $next($request);
    }
}
