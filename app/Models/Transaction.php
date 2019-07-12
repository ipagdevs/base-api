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
        return $this->belongsToMany(Individual::class, 'transaction_customers', 'transaction_id', 'customer_id');
    }

    public function products()
    {
        return $this->hasMany(TransactionProduct::class, 'transaction_id');
    }

    public function histories()
    {
        return $this->hasMany(TransactionHistory::class, 'transaction_id');
    }
}
