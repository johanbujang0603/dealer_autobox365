<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Auth;

class Language
{

    public function __construct(Application $app, Request $request)
    {
        $this->app = $app;
        $this->request = $request;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // dd(session('my_locale'));

        if (isset(Auth::user()->settings->language)) {
            // dd(Auth::user()->settings->language);
            $this->app->setLocale(session('my_locale', Auth::user()->settings->language));
        } else {
            $this->app->setLocale(session('my_locale', config('app.locale')));
        }


        return $next($request);
    }
}
