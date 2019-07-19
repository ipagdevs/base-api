<?php

namespace App\Services\Transaction;

use App\Models\Transaction;
use App\Services\Transaction\MethodService;
use App\Services\Transaction\TransactionDatabaseService;
use Ship\Containers\PaymentProcessor\Mappers\PaymentStrategyMapper;
use Ship\Containers\Transaction\Components\Transaction as TransactionPayload;

class TransactionService
{
    /**
     * @var TransactionDatabaseService
     */
    protected $transactionDatabaseService;

    /**
     * Process Transaction
     * @param Transaction $transaction
     * @param TransactionPayload $transactionPayload
     * @return Transaction
     */
    public function process(Transaction $transaction, TransactionPayload $transactionPayload)
    {
        $methodCollection = (new MethodService())
            ->getMethodCollection($transaction->user, $transaction->meioPagto);

        foreach ($methodCollection as $methodSetting) {
            $strategy = PaymentStrategyMapper::map($methodSetting->getCompany());
            $acquirerStrategy = new $strategy($transactionPayload, $methodSetting);
            $transactionPayload = $acquirerStrategy
                ->authorize()
                ->setLogger(base_path('storage/acquirers/%s.log'))
                ->call();

            //4 SAVE TRANSACTION AGAIN
            $transaction = $this->getTransactionDatabaseService()
                ->update($transaction, $transactionPayload);

            if ($methodSetting->hasAnotherAcquirer()
                && in_array($transactionPayload->getStatus(), [3, 7])
            ) {
                $transactionPayload->restart();

                $transaction = $this->getTransactionDatabaseService()
                    ->create($transaction->user, $transactionPayload);
                continue;
            }

            if (!in_array($transactionPayload->getStatus(), [3, 7])) {
                break;
            }
        }

        return $transaction;
    }

    protected function getTransactionDatabaseService()
    {
        if (!($this->transactionDatabaseService instanceof TransactionDatabaseService)) {
            $this->transactionDatabaseService = new TransactionDatabaseService();
        }

        return $this->transactionDatabaseService;
    }
}
