<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'resource'   => 'product',
            'attributes' => [
                'name'        => (string) $this->name,
                'description' => (string) $this->description,
                'price'       => (float) $this->price,
                'sku'         => (string) $this->sku,
            ],
        ];
    }
}
