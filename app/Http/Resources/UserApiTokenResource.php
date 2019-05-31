<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserApiTokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        parent::withoutWrapping();

        return [
            'api_id'    => $this->api_id,
            'api_token' => $this->api_token,
        ];
    }
}
