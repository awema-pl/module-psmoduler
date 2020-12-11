<?php

namespace AwemaPL\Psmoduler\Sections\Installations\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use AwemaPL\Psmoduler\Facades\Psmoduler;

class Installation
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (Psmoduler::canInstallation()){
            $route = Route::getRoutes()->match($request);
            $name = $route->getName();
            if (!in_array($name, config('psmoduler.routes.installation.expect'))){
                return redirect()->route('psmoduler.installation.index');
            }
        }
        return $next($request);
    }
}
