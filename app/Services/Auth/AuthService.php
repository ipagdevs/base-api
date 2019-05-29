<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Models\UserApiToken;
use App\Services\User\UserApiTokenService;
use Illuminate\Auth\AuthenticationException;

class AuthorizeService
{
    public function authenticate($login, $password)
    {
        $apiToken = UserApiToken::whereApiId($login)->whereApiToken($password)->first();

        throw_unless($apiToken, new AuthenticationException());

        $auth = (new UserApiTokenService())->validate($apiToken, $password);

        if (!$auth) {
            throw new AuthenticationException();
        }

        return $apiToken->user;
    }
}
