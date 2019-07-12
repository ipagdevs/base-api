<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionHistoryResource extends JsonResource
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
            'amount'           => (float) number_format($this->amount, 2, '.', ''),
            'type'             => (string) $this->operation_type,
            'status'           => (string) $this->status,
            'code'             => (string) $this->response_code,
            'message'          => (string) $this->response_message,
            'authorization'    => (string) $this->authorization_code,
            'id_authorization' => (string) $this->authorization_id,
            'nsu'              => (string) $this->authorization_nsu,
            'created_at'       => (string) $this->created_at,
        ];
    }
}
