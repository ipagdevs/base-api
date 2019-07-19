<?php

namespace App\Services\Transaction;

use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Ship\Containers\Transaction\Components\BoletoPaymentMethod;
use Ship\Containers\Transaction\Components\CreditCardPaymentMethod;
use Ship\Containers\Transaction\Components\Transaction as TransactionPayload;

class TransactionDatabaseService
{
    public function create(User $user, TransactionPayload $transactionPayload)
    {
        $_order = $transactionPayload->getOrder();
        $_customer = $_order->getCustomer();
        $_paymentMethod = $transactionPayload->getPaymentMethod();
        $transaction = $user->transactions()->create([
            'identificacao'      => $user->login,
            'statusPagamento'    => 1,
            'retornoTipo'        => 'xml',
            'numPedido'          => $_order->getOrderId(),
            'transValor'         => $_order->getAmount(),
            'parcelas'           => $_order->getInstallments(),
            'ip'                 => $_order->getIp(),
            'urlRetorno'         => $_order->getCallbackUrl(),

            'nomeCliente'        => $_customer->getName(),
            'emailCliente'       => $_customer->getEmail(),
            'docCliente'         => $_customer->getCpfCnpj(),
            'foneCliente'        => $_customer->getPhone(),
            'birthdate'          => $_customer->getBirthdate(),
            'enderecoCliente'    => $_customer->getBillingAddress()->getStreet(),
            'bairroCliente'      => $_customer->getBillingAddress()->getDistrict(),
            'numeroCliente'      => $_customer->getBillingAddress()->getNumber(),
            'complementoCliente' => $_customer->getBillingAddress()->getComplement(),
            'cidadeCliente'      => $_customer->getBillingAddress()->getCity(),
            'estadoCliente'      => $_customer->getBillingAddress()->getState(),
            'cepCliente'         => $_customer->getBillingAddress()->getZipcode(),
            'transData'          => Carbon::now()->toDateTimeString(),
            'meioPagto'          => '',
        ]);

        $customer = $transaction->customer()->create([
            'name'      => $_customer->getName(),
            'cpf_cnpj'  => $_customer->getCpfCnpj(),
            'birthdate' => $_customer->getBirthdate(),
            'email'     => $_customer->getEmail(),
        ]);

        $customer->contacts()->create([
            'type'   => 'mobile',
            'number' => $_customer->getPhone(),
        ]);

        $customer->billing()->create([
            'type'       => 'billing',
            'street'     => $_customer->getBillingAddress()->getStreet(),
            'district'   => $_customer->getBillingAddress()->getDistrict(),
            'number'     => $_customer->getBillingAddress()->getNumber(),
            'complement' => $_customer->getBillingAddress()->getComplement(),
            'city'       => $_customer->getBillingAddress()->getCity(),
            'state'      => $_customer->getBillingAddress()->getState(),
            'zipcode'    => $_customer->getBillingAddress()->getZipcode(),
        ]);

        $customer->shipping()->create([
            'type'       => 'shipping',
            'street'     => $_customer->getShippingAddress()->getStreet(),
            'district'   => $_customer->getShippingAddress()->getDistrict(),
            'number'     => $_customer->getShippingAddress()->getNumber(),
            'complement' => $_customer->getShippingAddress()->getComplement(),
            'city'       => $_customer->getShippingAddress()->getCity(),
            'state'      => $_customer->getShippingAddress()->getState(),
            'zipcode'    => $_customer->getShippingAddress()->getZipcode(),
        ]);

        foreach ($_order->getProducts() as $_product) {
            $transaction->products()->create([
                'name'        => $_product->getName(),
                'unit_price'  => $_product->getUnitPrice(),
                'quantity'    => $_product->getQuantity(),
                'sku'         => $_product->getSku(),
                'description' => $_product->getDescription(),
            ]);
        }

        if ($transactionPayload->getPaymentMethod() instanceof CreditCardPaymentMethod) {
            $_creditCard = $transactionPayload->getPaymentMethod()->getCreditCard();
            $transaction->card()->create([
                'signature'    => $_creditCard->getSignature(),
                'holder'       => $_creditCard->getHolder(),
                'number'       => $_creditCard->getMaskedNumber(),
                'expiry_month' => $_creditCard->getExpiryMonth(),
                'expiry_year'  => $_creditCard->getExpiryYear(),
                'brand'        => $_creditCard->getBrand(),
                'holder'       => $_creditCard->getHolder(),
            ]);

            $transaction->meioPagto = $_creditCard->getBrand();
        } elseif ($transactionPayload->getPaymentMethod() instanceof BoletoPaymentMethod) {
            $_boleto = $transactionPayload->getPaymentMethod()->getBoleto();
            $transaction->vencto = $_boleto->getExpiryDate();
            $transaction->meioPagto = $_boleto->getCompany();
            $transaction->save();

            foreach ($_boleto->getInstructions() as $instruction) {
                $transaction->instructions()->create([
                    'key'   => 'instructions',
                    'value' => $instruction,
                ]);
            }

            foreach ($_boleto->getDemonstratives() as $demonstrative) {
                $transaction->instructions()->create([
                    'key'   => 'demonstratives',
                    'value' => $demonstrative,
                ]);
            }
        }

        return $transaction;
    }

    public function update(Transaction $transaction, TransactionPayload $transactionPayload)
    {
        $transaction->update([
            'transId'           => $transactionPayload->getTid(),
            'authId'            => $transactionPayload->getAuthId(),
            'authValor'         => $transactionPayload->getAuthAmount(),
            'authData'          => $transactionPayload->getAuthDate(),
            'capturaValor'      => $transactionPayload->getCaptureAmount(),
            'capturaData'       => $transactionPayload->getCaptureDate(),
            'numSeqUnico'       => $transactionPayload->getUniqueSequentialNumber(),
            'referrer'          => $transactionPayload->getReferrer(),
            'free'              => $transactionPayload->getFree(),
            'operadora'         => $transactionPayload->getAcquirer(),
            'operadoraCodigo'   => $transactionPayload->getAcquirerCode(),
            'operadoraMensagem' => $transactionPayload->getAcquirerMessage(),
            'statusPagamento'   => $transactionPayload->getStatus(),
            'urlAutenticacao'   => $transactionPayload->getAuthenticationUrl(),
            'linhaDigitavel'    => $transactionPayload->getDigitableLine(),
        ]);

        return $transaction;
    }
}
