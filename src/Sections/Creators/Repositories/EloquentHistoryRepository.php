<?php

namespace AwemaPL\Psmoduler\Sections\Creators\Repositories;

use AwemaPL\Psmoduler\Sections\Creators\Models\History;
use AwemaPL\Psmoduler\Sections\Creators\Repositories\Contracts\HistoryRepository;
use AwemaPL\Psmoduler\Sections\Creators\Scopes\EloquentHistoryScopes;
use AwemaPL\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\Auth;

class EloquentHistoryRepository extends BaseRepository implements HistoryRepository
{
    protected $searchable = [

    ];

    public function entity()
    {
        return History::class;
    }

    public function scope($request)
    {
        // apply build-in scopes
        parent::scope($request);

        // apply custom scopes
        $this->entity = (new EloquentHistoryScopes($request))->scope($this->entity);
        return $this;
    }

    /**
     * Create new role
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        $data['user_id'] = Auth::id();
        return History::create($data);
    }

}
