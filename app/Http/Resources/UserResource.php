<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'         => $this->id,
            'resource'   => 'user',
            'attributes' => [
                'name'       => $this->name,
                'email'      => $this->email,
                'api_id'     => $this->api_id,
                'api_token'  => $this->api_token,
                'created_at' => $this->created_at,
            ],
        ];
    }
}
