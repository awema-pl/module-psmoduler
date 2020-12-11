<?php

namespace AwemaPL\Psmoduler\Sections\Creators\Http\Controllers;

use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Psmoduler\Sections\Creators\Http\Requests\StoreCreate;
use AwemaPL\Psmoduler\Sections\Creators\Repositories\Contracts\HistoryRepository;
use AwemaPL\Psmoduler\Sections\Creators\Resources\EloquentHistory;
use AwemaPL\Psmoduler\Sections\Creators\Services\ModuleCreatorService;
use AwemaPL\Psmoduler\Sections\Creators\Services\ModuleNameService;
use AwemaPL\Psmoduler\Sections\Installations\Http\Requests\StoreInstallation;
use AwemaPL\Permission\Repositories\Contracts\PermissionRepository;
use AwemaPL\Permission\Resources\EloquentPermission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreatorController extends Controller
{

    /**
     * Histories repository instance
     *
     * @var HistoryRepository
     */
    protected $histories;

    /** @var ModuleCreatorService $moduleCreator  */
    protected $moduleCreator;

    /** @var ModuleNameService $moduleName */
    protected $moduleName;

    public function __construct(HistoryRepository $histories, ModuleCreatorService $moduleCreator, ModuleNameService $moduleName)
    {
        $this->histories = $histories;
        $this->moduleCreator = $moduleCreator;
        $this->moduleName = $moduleName;
    }

    /**
     * Display create module form
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('psmoduler::sections.creators.index');
    }

    /**
     * Request scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function scope(Request $request)
    {
        return EloquentHistory::collection(
            $this->histories->scope($request)
                ->isOwner()
                ->latest()->smartPaginate()
        );
    }

    /**
     * Download module
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function download($filename)
    {
        $path = 'temp/psmoduler/' . $filename . '.zip';
        if (!Storage::exists($path)){
            abort(404);
        }
        session()->push('terminate-delete-files', $path);
        return Storage::download($path);
    }

    /**
     * Create module
     *
     * @param StoreCreate $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(StoreCreate $request)
    {
        $withPackage= $request->with_package;
        $nameModule = $this->moduleName->buildName($request->name_module);
        $dirTempName = $this->moduleCreator->buildZipModule($nameModule, $withPackage);
        $this->histories->create(['name' => $nameModule, 'with_package' =>$withPackage]);
        return response()->json([
            'redirectUrl' =>route('psmoduler.creator.download', ['filename' => $dirTempName]),
        ]);
    }
}
