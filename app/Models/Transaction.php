<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transacao';

    protected $guarded = ['id'];

    protected $dates = ['transData'];

    public function customer()
    {
        return $this->belongsToMany(
            Individual::class,
            'transaction_customers',
            'transaction_id',
            'customer_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'idPessoa');
    }

    public function products()
    {
        return $this->hasMany(TransactionProduct::class, 'transaction_id');
    }

    public function card()
    {
        return $this->hasMany(TransactionCard::class, 'transaction_id');
    }

    public function instructions()
    {
        return $this->hasMany(TransactionExtraField::class, 'transaction_id')
            ->where('key', 'instructions');
    }

    public function demonstratives()
    {
        return $this->hasMany(TransactionExtraField::class, 'transaction_id')
            ->where('key', 'demonstratives');
    }

    public function histories()
    {
        return $this->hasMany(TransactionHistory::class, 'transaction_id');
    }

    public function getStatusMessage()
    {
        switch ($this->statusPagamento) {
            case 1:
                return 'created';
            case 2:
                return 'waiting_payment';
            case 3:
                return 'canceled';
            case 4:
                return 'pending';
            case 5:
                return 'authorized';
            case 6:
                return 'partial authorized';
            case 7:
                return 'failed';
            case 8:
                return 'captured';
            case 9:
                return 'chargebacked';
            case 10:
                return 'disputed';
        }
    }
}
