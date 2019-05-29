<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function view(Request $request)
    {

        $user = User::find(1);

        // Creating a token without scopes...
        $token = $user->createToken('Token Name')->accessToken;

        return response()->json([$token], 200);
    }
}
