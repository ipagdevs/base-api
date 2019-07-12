<?php

namespace App\Observers;

use App\Models\Transaction;
use Carbon\Carbon;

class TransactionObserver
{
    /**
     * Handle the transaction "created" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function created(Transaction $transaction)
    {
        $transaction->histories()->create([
            'amount'         => $transaction->transValor,
            'operation_type' => 'created',
            'status'         => 'succeeded',
            'created_at'     => Carbon::now()->toDateTimeString(),
        ]);
    }

    /**
     * Handle the transaction "updated" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function updated(Transaction $transaction)
    {
        $operationType = null;
        switch ($transaction->statusPagamento) {
            case 1:
                $operationType = 'created';
                break;
            case 3:
                $operationType = 'voided';
                break;
            case 4:
                $operationType = 'pending';
                break;
            case 5:
                $operationType = 'authorized';
                break;
            case 7:
                $operationType = 'denied';
                break;
            case 8:
                $operationType = 'captured';
                break;
            case 9:
                $operationType = 'chargebacked';
                break;
            case 10:
                $operationType = 'disputed';
                break;
        }

        if ($operationType === null) {
            return;
        }

        $transactionHistory = $transaction->histories()->firstOrNew([
            'operation_type' => $operationType,
        ], [
            'status'             => 'succeeded',
            'amount'             => $transaction->transValor,
            'response_code'      => $transaction->operadoraCodigo,
            'response_message'   => $transaction->operadoraMensagem,
            'authorization_id'   => $transaction->transId,
            'authorization_code' => $transaction->authId,
            'authorization_nsu'  => $transaction->numSeqUnico,
            'amount'             => $transaction->transValor,
            'created_at'         => Carbon::now()->toDateTimeString(),
        ]);
        $transactionHistory->save();
    }

    /**
     * Handle the transaction "deleted" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function deleted(Transaction $transaction)
    {
        //
    }

    /**
     * Handle the transaction "restored" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function restored(Transaction $transaction)
    {
        //
    }

    /**
     * Handle the transaction "force deleted" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function forceDeleted(Transaction $transaction)
    {
        //
    }
}
