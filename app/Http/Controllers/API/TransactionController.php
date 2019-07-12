<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\Transaction\TransactionResource;
use App\Http\Resources\Transaction\TransactionsResource;
use App\Models\Transaction;
use App\Services\Transaction\TransactionPayloadService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Show a List of Transactions
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $transactions = $request->user()->transactions()->orderBy('id', 'desc')->paginate();

        throw_if($transactions->isEmpty(), new \Exception());

        return new TransactionsResource($transactions, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction);
    }

    /**
     * Create a new Transaction
     * @param TransactionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionRequest $request)
    {
        //1 HYDRATION OK
        $transactionPayload = (new TransactionPayloadService())->hydrate((array) $request->all());

        //2 DATABASE
        $_order = $transactionPayload->getOrder();
        $_customer = $_order->getCustomer();
        $_paymentMethod = $transactionPayload->getPaymentMethod();
        $transaction = $request->user()->transactions()->create([
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

        $_products = $_order->getProducts();

        foreach ($_products as $_product) {
            $transaction->products()->create([
                'name'        => $_product->getName(),
                'unit_price'  => $_product->getUnitPrice(),
                'quantity'    => $_product->getQuantity(),
                'sku'         => $_product->getSku(),
                'description' => $_product->getDescription(),
            ]);
        }

        //3 PROCESS

        //4 SAVE TRANSACTION AGAIN

        //5 RETURN

        return new TransactionResource($transaction, 201);
    }
}
