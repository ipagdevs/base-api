<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionBoletoRequest;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\Transaction\TransactionResource;
use App\Http\Resources\Transaction\TransactionsResource;
use App\Models\Transaction;
use App\Services\Transaction\TransactionDatabaseService;
use App\Services\Transaction\TransactionPayloadService;
use App\Services\Transaction\TransactionService;
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
    public function storeCard(TransactionRequest $request)
    {
        $transactionPayload = (new TransactionPayloadService())
            ->hydrate((array) $request->all(), 'creditcard');

        $transaction = (new TransactionDatabaseService())
            ->create($request->user(), $transactionPayload);

        //3 PROCESS (TWO WAYS)
        // 3.1 CREDITCARD (GET ALL ACTIVE METHODS FROM CCs BRAND)
        $transaction = (new TransactionService())
            ->process($transaction, $transactionPayload);

        // Antifraude...
        // Receivavles...
        // Callbacks...

        return new TransactionResource($transaction, 201);
    }

    /**
     * Create a new Transaction
     * @param TransactionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function storeBoleto(TransactionBoletoRequest $request)
    {
        $transactionPayload = (new TransactionPayloadService())
            ->hydrate((array) $request->all(), 'boleto');

        $transaction = (new TransactionDatabaseService())
            ->create($request->user(), $transactionPayload);

        //3 PROCESS (TWO WAYS)
        // 3.1 CREDITCARD (GET ALL ACTIVE METHODS FROM CCs BRAND)
        // $transaction = (new TransactionService())
        //     ->process($transaction, $transactionPayload);

        // Antifraude...
        // Receivavles...
        // Callbacks...

        return new TransactionResource($transaction, 201);
    }
}
