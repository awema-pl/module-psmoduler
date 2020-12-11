<?php

namespace AwemaPL\Psmoduler\Sections\Creators\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use AwemaPL\Psmoduler\Facades\Psmoduler;
use Illuminate\Support\Facades\Storage;

class StorageDownload
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
        return $next($request);
    }

    /**
     * Terminate
     *
     * @param $request
     * @param $response
     */
    public function terminate($request, $response)
    {
        $paths = (array) session()->pull('terminate-delete-files', []);
        foreach ($paths as $path){
            Storage::delete($path);
        }
    }
}
