<?php

namespace AwemaPL\Psmoduler\Sections\Informations\Http\Controllers;

use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Psmoduler\Sections\Creators\Http\Requests\StoreCreate;
use AwemaPL\Psmoduler\Sections\Creators\Repositories\Contracts\HistoryRepository;
use AwemaPL\Psmoduler\Sections\Creators\Resources\EloquentHistory;
use AwemaPL\Psmoduler\Sections\Creators\Services\ModuleCreatorService;
use AwemaPL\Psmoduler\Sections\Creators\Services\ModuleNameService;
use AwemaPL\Psmoduler\Sections\Installations\Http\Requests\StoreInstallation;
use AwemaPL\Psmoduler\Sections\Users\Repositories\Contracts\UserRepository;
use AwemaPL\Psmoduler\Sections\Informations\Resources\EloquentInformation;
use AwemaPL\Permission\Repositories\Contracts\PermissionRepository;
use AwemaPL\Permission\Resources\EloquentPermission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InformationController extends Controller
{
    /**
     * Information
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scope(Request $request)
    {
        return response()->json(new EloquentInformation($request));
    }
}
