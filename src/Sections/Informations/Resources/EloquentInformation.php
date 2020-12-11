<?php

namespace AwemaPL\Psmoduler\Sections\Informations\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class EloquentInformation extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $request->user();
        $membership = $user->membership;
        $option = optional($membership)->option;
        return [
            'membership'=>[
                'user' =>[
                    'email' =>$user->email,
                ],
                'option' =>[
                    'name' =>optional($option)->name,
                ],
                'expires_at' => optional($membership)->expires_at,
            ],
        ];

    }
}
