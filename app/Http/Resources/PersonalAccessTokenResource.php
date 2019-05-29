<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonalAccessTokenResource extends JsonResource
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
            'token'      => $this->accessToken,
            'created_at' => $this->token->created_at,
            'expires_at' => $this->token->expires_at->toDateTimeString(),
            // 'user'       => new UserResource($this->token->user),
        ];
    }
}
