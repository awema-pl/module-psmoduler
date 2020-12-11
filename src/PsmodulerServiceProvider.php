<?php

namespace AwemaPL\Psmoduler;

use AwemaPL\BaseJS\AwemaProvider;
use AwemaPL\Psmoduler\Listeners\EventSubscriber;
use AwemaPL\Psmoduler\Sections\Tokens\Repositories\Contracts\TokenRepository;
use AwemaPL\Psmoduler\Sections\Tokens\Repositories\EloquentTokenRepository;
use AwemaPL\Psmoduler\Sections\Users\Repositories\Contracts\UserRepository;
use AwemaPL\Psmoduler\Sections\Users\Repositories\EloquentUserRepository;
use AwemaPL\Navigation\Middlewares\AddNavigationComponent;
use AwemaPL\Psmoduler\Sections\Creators\Http\Middleware\StorageDownload;
use AwemaPL\Psmoduler\Sections\Creators\Repositories\Contracts\HistoryRepository;
use AwemaPL\Psmoduler\Sections\Creators\Repositories\EloquentHistoryRepository;
use AwemaPL\Psmoduler\Sections\Installations\Http\Middleware\GlobalMiddleware;
use AwemaPL\Psmoduler\Sections\Installations\Http\Middleware\GroupMiddleware;
use AwemaPL\Psmoduler\Sections\Installations\Http\Middleware\Installation;
use AwemaPL\Psmoduler\Sections\Installations\Http\Middleware\RouteMiddleware;
use AwemaPL\Psmoduler\Contracts\Psmoduler as PsmodulerContract;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;

class PsmodulerServiceProvider extends AwemaProvider
{

    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'psmoduler');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'psmoduler');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->bootMiddleware();
        app('psmoduler')->includeLangJs();
        app('psmoduler')->menuMerge();
        app('psmoduler')->mergePermissions();
        Event::subscribe(EventSubscriber::class);
        parent::boot();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/psmoduler.php', 'psmoduler');
        $this->mergeConfigFrom(__DIR__ . '/../config/psmoduler-menu.php', 'psmoduler-menu');
        $this->app->bind(PsmodulerContract::class, Psmoduler::class);
        $this->app->singleton('psmoduler', PsmodulerContract::class);
        $this->registerRepositories();
        parent::register();
    }


    public function getPackageName(): string
    {
        return 'psmoduler';
    }

    public function getPath(): string
    {
        return __DIR__;
    }

    /**
     * Register and bind package repositories
     *
     * @return void
     */
    protected function registerRepositories()
    {
        $this->app->bind(HistoryRepository::class, EloquentHistoryRepository::class);
        $this->app->bind(TokenRepository::class, EloquentTokenRepository::class);
    }

    /**
     * Boot middleware
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function bootMiddleware()
    {
        $this->bootGlobalMiddleware();
        $this->bootRouteMiddleware();
        $this->bootGroupMiddleware();
    }

    /**
     * Boot route middleware
     */
    private function bootRouteMiddleware()
    {
        $router = app('router');
        $router->aliasMiddleware('psmoduler', RouteMiddleware::class);
    }

    /**
     * Boot group middleware
     */
    private function bootGroupMiddleware()
    {
        $kernel = $this->app->make(\Illuminate\Contracts\Http\Kernel::class);
        $kernel->appendMiddlewareToGroup('web', GroupMiddleware::class);
        $kernel->appendMiddlewareToGroup('web', Installation::class);
    }

    /**
     * Boot global middleware
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function bootGlobalMiddleware()
    {
        $kernel = $this->app->make(\Illuminate\Contracts\Http\Kernel::class);
        $kernel->pushMiddleware(GlobalMiddleware::class);
        $kernel->pushMiddleware(StorageDownload::class);
    }
}
