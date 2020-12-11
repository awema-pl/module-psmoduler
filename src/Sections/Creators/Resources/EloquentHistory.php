<?php

namespace AwemaPL\Psmoduler\Sections\Creators\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class EloquentHistory extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'with_package' => $this->with_package,
            'created_at' =>$this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
