<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerAddressResource extends JsonResource
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
            // 'type'       => (string) $this->type,
            'street'     => (string) $this->street,
            'number'     => (string) $this->number,
            'district'   => (string) $this->district,
            'complement' => (string) $this->complement,
            'city'       => (string) $this->city,
            'state'      => (string) $this->state,
            'zipcode'    => (string) $this->zipcode,
        ];
    }
}
