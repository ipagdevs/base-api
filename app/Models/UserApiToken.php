<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserApiToken extends Model
{
    public $table = 'user_api_tokens';

    public $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function setSaltAttribute($value)
    {
        $this->attributes['salt'] = encrypt($value);
    }

    public function getSaltAttribute()
    {
        return decrypt($this->attributes['salt']);
    }

    public function setSignatureAttribute($value)
    {
        $this->attributes['signature'] = encrypt($value);
    }

    public function getSignatureAttribute()
    {
        return decrypt($this->attributes['signature']);
    }
}
