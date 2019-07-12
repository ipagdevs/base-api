<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Individual extends Model
{
    protected $table = 'individuals';

    protected $guarded = ['id'];

    public function contacts()
    {
        return $this->hasMany(IndividualContact::class, 'individual_id');
    }

    public function addresses()
    {
        return $this->hasMany(IndividualAddress::class, 'individual_id');
    }

    public function shipping()
    {
        return $this->hasOne(IndividualAddress::class, 'individual_id')
            ->where('type', 'shipping');
    }

    public function billing()
    {
        return $this->hasOne(IndividualAddress::class, 'individual_id')
            ->where('type', 'billing');
    }
}
