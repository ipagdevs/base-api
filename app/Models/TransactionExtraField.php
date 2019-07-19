<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionExtraField extends Model
{
    protected $table = 'transaction_extra_fields';

    protected $guarded = ['id'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
