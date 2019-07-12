<?php

namespace App\Http\Resources\Transaction;

use App\Http\Resources\Transaction\CustomerAddressResource;
use App\Http\Resources\Transaction\CustomerContactResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'nome'      => (string) $this->name,
            'cpfCnpj'   => (string) $this->cpf_cnpj,
            'email'     => (string) $this->email,
            'phone'     => new CustomerContactResource($this->contacts()->first()),
            'birthdate' => (string) (!empty($this->birthdate) && $this->birthdate != '0000-00-00') ? Carbon::parse($this->birthdate)->toDateString() : null,
            'billing'   => new CustomerAddressResource($this->billing),
            'shipping'  => new CustomerAddressResource($this->shipping),
        ];
    }
}
