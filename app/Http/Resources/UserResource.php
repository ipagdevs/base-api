<?php

namespace App\Http\Resources;

use App\Http\Resources\UserApiTokensResource;
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
                'api_tokens' => new UserApiTokensResource($this->apiTokens),
                'created_at' => !is_null($this->created_at) ? $this->created_at->toDateTimeString() : null,
            ],
        ];
    }
}
