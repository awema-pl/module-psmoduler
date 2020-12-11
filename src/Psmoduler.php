<?php

namespace AwemaPL\Psmoduler;

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Routing\Router;
use AwemaPL\Psmoduler\Contracts\Psmoduler as PsmodulerContract;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class Psmoduler implements PsmodulerContract
{
    /** @var Router $router */
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Routes
     */
    public function routes()
    {
        if ($this->isActiveRoutes()) {
            if ($this->isActiveInstallationRoutes()) {
                $this->installationRoutes();
            }
            if ($this->isActiveCreatorRoutes()) {
                $this->creatorRoutes();
            }
            if ($this->isActiveExampleRoutes()) {
                $this->exampleRoutes();
            }
            if ($this->isActiveInformationRoutes()) {
                $this->informationRoutes();
            }
        }
    }

    /**
     * Installation routes
     */
    protected function installationRoutes()
    {
        $prefix = config('psmoduler.routes.installation.prefix');
        $namePrefix = config('psmoduler.routes.installation.name_prefix');
        $this->router->prefix($prefix)->name($namePrefix)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Psmoduler\Sections\Installations\Http\Controllers\InstallationController@index')
                ->name('index');
            $this->router->post('/', '\AwemaPL\Psmoduler\Sections\Installations\Http\Controllers\InstallationController@store')
                ->name('store');
        });

    }

    /**
     * Creator routes
     */
    protected function creatorRoutes()
    {

        $prefix = config('psmoduler.routes.creator.prefix');
        $namePrefix = config('psmoduler.routes.creator.name_prefix');
        $middleware = config('psmoduler.routes.creator.middleware');
        $this->router->prefix($prefix)->name($namePrefix)->middleware($middleware)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Psmoduler\Sections\Creators\Http\Controllers\CreatorController@index')
                ->name('index');
            $this->router
                ->post('/', '\AwemaPL\Psmoduler\Sections\Creators\Http\Controllers\CreatorController@store')
                ->name('store');
            $this->router
                ->get('/histories', '\AwemaPL\Psmoduler\Sections\Creators\Http\Controllers\CreatorController@scope')
                ->name('scope');
            $this->router
                ->get('/download/{filename}', '\AwemaPL\Psmoduler\Sections\Creators\Http\Controllers\CreatorController@download')
                ->name('download');
        });
    }

    /**
     * Example routes
     */
    protected function exampleRoutes()
    {

        $prefix = config('psmoduler.routes.example.prefix');
        $namePrefix = config('psmoduler.routes.example.name_prefix');
        $middleware = config('psmoduler.routes.example.middleware');
        $this->router->prefix($prefix)->name($namePrefix)->middleware($middleware)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Psmoduler\Sections\Examples\Http\Controllers\ExampleController@index')
                ->name('index');
            $this->router
                ->get('/', '\AwemaPL\Psmoduler\Sections\Examples\Http\Controllers\ExampleController@index')
                ->name('index');
            $this->router
                ->get('/virtual-tour-from-beginning', '\AwemaPL\Psmoduler\Sections\Examples\Http\Controllers\ExampleController@virtualTourFromBeginning')
                ->name('virtual_tour_from_beginning');
        });
    }

    /**
     * Information routes
     */
    protected function informationRoutes()
    {
        $prefix = config('psmoduler.routes.information.prefix');
        $namePrefix = config('psmoduler.routes.information.name_prefix');
        $middleware = config('psmoduler.routes.information.middleware');
        $this->router->prefix($prefix)->name($namePrefix)->middleware($middleware)->group(function () {
            $this->router
                ->get('/informations', '\AwemaPL\Psmoduler\Sections\Informations\Http\Controllers\InformationController@scope')
                ->name('scope');
        });
    }


    /**
     * Can installation
     *
     * @return bool
     */
    public function canInstallation()
    {
        $canForPermission = $this->canInstallForPermission();
        return $this->isActiveRoutes()
            && $this->isActiveInstallationRoutes()
            && $canForPermission
            && !$this->isMigrated();
    }

    /**
     * Is migrated
     *
     * @return bool
     */
    public function isMigrated()
    {
        $tablesInDb = array_map('reset', \DB::select('SHOW TABLES'));
        $tables = array_values(config('psmoduler.database.tables'));
        foreach ($tables as $table) {
            if (!in_array($table, $tablesInDb)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Is active routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    public function isActiveRoutes()
    {
        return config('psmoduler.routes.active');
    }

    /**
     * Is active psmoduler routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    public function isActiveCreatorRoutes()
    {
        return config('psmoduler.routes.creator.active');
    }

    /**
     * Is active installation routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private function isActiveInstallationRoutes()
    {
        return config('psmoduler.routes.installation.active');
    }

    /**
     * Is active example routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private function isActiveExampleRoutes()
    {
        return config('psmoduler.routes.example.active');
    }


    /**
     * Is active information routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private function isActiveInformationRoutes()
    {
        return config('psmoduler.routes.information.active');
    }

    /**
     * Include lang JS
     */
    public function includeLangJs()
    {
        $lang = config('indigo-layout.frontend.lang', []);
        $lang = array_merge_recursive($lang, app(\Illuminate\Contracts\Translation\Translator::class)->get('psmoduler::js')?:[]);
        app('config')->set('indigo-layout.frontend.lang', $lang);
    }

    /**
     * Can install for permission
     *
     * @return bool
     */
    private function canInstallForPermission()
    {
        $userClass = config('auth.providers.users.model');
        if (!method_exists($userClass, 'hasRole')) {
            return true;
        }
        if ($user = request()->user() ?? null) {
            return $user->can(config('psmoduler.installation.auto_redirect.permission'));
        }
        return false;
    }

    /**
     * Menu merge in navigation
     */
    public function menuMerge()
    {
        if ($this->canMergeMenu()) {
            $psmodulerNav = config('psmoduler-menu.navs', []);
            $navTemp = config('temp_navigation.navs', []);
            $nav = array_merge_recursive($navTemp, $psmodulerNav);
            config(['temp_navigation.navs' => $nav]);
        }
    }

    /**
     * Can merge menu
     *
     * @return boolean
     */
    private function canMergeMenu()
    {
        return !!config('psmoduler-menu.merge_to_navigation') && self::isMigrated();
    }

    /**
     * Execute module migrations
     */
    public function migrate()
    {
        Artisan::call('migrate', ['--force' => true, '--path'=>'vendor/awema-pl/module-psmoduler/database/migrations']);
    }


    /**
     * Install package
     */
    public function install()
    {
        $this->migrate();
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
    }

    /**
     * Add permissions for module permission
     */
    public function mergePermissions()
    {
        if ($this->canMergePermissions()){
            $psmodulerPermissions = config('psmoduler.permissions');
            $tempPermissions = config('temp_permission.permissions', []);
            $permissions = array_merge_recursive($tempPermissions, $psmodulerPermissions);
            config(['temp_permission.permissions' => $permissions]);
        }
    }

    /**
     * Can merge permissions
     *
     * @return boolean
     */
    private function canMergePermissions()
    {
        return !!config('psmoduler.merge_permissions');
    }

}
