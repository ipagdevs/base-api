<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionProductResource extends JsonResource
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
            'sku'         => (string) $this->sku,
            'name'        => (string) $this->name,
            'unitPrice'   => (float) $this->unit_price,
            'quantity'    => (int) $this->quantity,
            'description' => (string) $this->description,
        ];
    }
}
