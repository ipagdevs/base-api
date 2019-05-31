<?php

namespace App\Models;

use App\Models\Product;
use App\Services\User\UserApiTokenService;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    const USERNAME = 'api_id';
    const PASSWORD = 'api_token';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'user_id');
    }

    public function apiTokens()
    {
        return $this->hasMany(UserApiToken::class, 'user_id');
    }

    public function generateApiToken()
    {
        return (new UserApiTokenService())->generate($this);
    }
}
