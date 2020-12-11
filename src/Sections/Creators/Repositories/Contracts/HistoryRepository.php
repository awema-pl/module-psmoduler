<?php

namespace AwemaPL\Psmoduler\Sections\Creators\Repositories\Contracts;

use Illuminate\Http\Request;

interface HistoryRepository
{
    /**
     * Create history
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Scope history
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scope($request);
}
