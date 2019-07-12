<?php

namespace App\Services\Transaction;

use Ship\Containers\Transaction\Components\Transaction;

class TransactionPayloadService
{
    public function hydrate(array $payload = [])
    {
        // 1: Hydrate TransactionPayload
        $paymentType = $payload['payment']['type'] ?? null;
        if (in_array($paymentType, ['creditcard', 'debitcard'])) {
            $paymentMethodPayload = [
                'type'           => $paymentType,
                'capture'        => $payload['payment']['capture'] ?? null,
                'acquirerToken'  => $payload['payment']['acquirer_token'] ?? null,
                'fingerprint'    => $payload['payment']['fingerprint'] ?? null,
                'recurring'      => $payload['payment']['recurring'] ?? null,
                'softdescriptor' => $payload['payment']['softdescriptor'] ?? null,
                'creditCard'     => [
                    'brand'       => $payload['payment']['creditcard']['brand'] ?? null,
                    'number'      => $payload['payment']['creditcard']['number'] ?? null,
                    'holder'      => $payload['payment']['creditcard']['holder'] ?? null,
                    'expiryMonth' => $payload['payment']['creditcard']['month'] ?? null,
                    'expiryYear'  => $payload['payment']['creditcard']['year'] ?? null,
                    'cvv'         => $payload['payment']['creditcard']['cvv'] ?? null,
                    'token'       => $payload['payment']['creditcard']['token'] ?? null,
                ],
            ];
        } elseif ($paymentType == 'boleto') {
            $paymentMethodPayload = [
                'type'        => $paymentType,
                'fingerprint' => $payload['payment']['fingerprint'] ?? null,
                'boleto'      => [],
            ];
        }

        $transactionPayload = (new Transaction())->hydrate([
            'order'         => [
                'orderId'            => $payload['order']['orderId'] ?? null,
                'amount'             => $payload['order']['amount'] ?? null,
                'installments'       => $payload['order']['installments'] ?? null,
                'currency'           => 'BRL',
                'discount'           => $payload['order']['discount'] ?? null,
                'freight'            => $payload['order']['freight'] ?? null,
                'freightDescription' => $payload['order']['freightDescription'] ?? null,
                'ip'                 => $payload['order']['ip'] ?? null,
                'callbackUrl'        => $payload['order']['callbackUrl'] ?? null,
                'customer'           => [
                    'visitorId'       => $payload['order']['customer']['visitorId'] ?? null,
                    'name'            => $payload['order']['customer']['name'] ?? null,
                    'cpfCnpj'         => $payload['order']['customer']['cpfCnpj'] ?? null,
                    'phone'           => $payload['order']['customer']['phone'] ?? null,
                    'birthdate'       => $payload['order']['customer']['birthdate'] ?? null,
                    'billingAddress'  => [
                        'zipcode'    => $payload['order']['customer']['billing']['zipcode'] ?? null,
                        'street'     => $payload['order']['customer']['billing']['street'] ?? null,
                        'number'     => $payload['order']['customer']['billing']['number'] ?? null,
                        'district'   => $payload['order']['customer']['billing']['district'] ?? null,
                        'complement' => $payload['order']['customer']['billing']['complement'] ?? null,
                        'city'       => $payload['order']['customer']['billing']['city'] ?? null,
                        'state'      => $payload['order']['customer']['billing']['state'] ?? null,
                    ],
                    'shippingAddress' => [
                        'zipcode'    => $payload['order']['customer']['shipping']['zipcode'] ?? null,
                        'street'     => $payload['order']['customer']['shipping']['street'] ?? null,
                        'number'     => $payload['order']['customer']['shipping']['number'] ?? null,
                        'district'   => $payload['order']['customer']['shipping']['district'] ?? null,
                        'complement' => $payload['order']['customer']['shipping']['complement'] ?? null,
                        'city'       => $payload['order']['customer']['shipping']['city'] ?? null,
                        'state'      => $payload['order']['customer']['shipping']['state'] ?? null,
                    ],
                ],
                'products'           => $payload['order']['products'] ?? [],
            ],
            'paymentMethod' => $paymentMethodPayload,
        ]);

        return $transactionPayload;
    }
}
