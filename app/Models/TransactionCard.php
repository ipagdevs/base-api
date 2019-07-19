<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionCard extends Model
{
    protected $table = 'transaction_cards';

    protected $guarded = ['id'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
