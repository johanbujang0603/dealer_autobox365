<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Currency
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
        if (isset(Auth::user()->settings->currency)) {
            config(['app.currency' => session('my_currency', Auth::user()->settings->currency)]);
        } else {
            config(['app.currency' => session('my_currency', 'USD')]);
        }

        return $next($request);
    }
}
