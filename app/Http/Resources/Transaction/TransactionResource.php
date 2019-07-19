<?php

namespace App\Http\Resources\Transaction;

use App\Http\Resources\Transaction\CustomerResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'resource'   => 'transaction',
            'attributes' => [
                'order_id'           => (string) $this->numPedido,
                'amount'             => (float) $this->transValor,
                'callback_url'       => (string) $this->urlRetorno,
                'tid'                => (string) $this->transId,
                'installments'       => (int) $this->parcelas,
                'captured_amount'    => (float) $this->capturaValor,
                'captured_at'        => (string) $this->capturaData,
                'acquirer'           => (string) $this->operadora,
                'acquirer_message'   => (string) $this->operadoraMensagem,
                'acquirer_code'      => (string) $this->operadoraCodigo,
                'url_authentication' => (string) $this->urlAutenticacao,
                'status'             => [
                    'code'    => (int) $this->statusPagamento,
                    'message' => (string) $this->getStatusMessage(),
                ],
                'customer'           => new CustomerResource($this->customer()->first()),
                'products'           => new TransactionProductsResource($this->products),
                'history'            => new TransactionHistoriesResource($this->histories),
                'created_at'         => (string) $this->created_at,
                'updated_at'         => (string) $this->updated_at,
            ],
        ];
    }
}
