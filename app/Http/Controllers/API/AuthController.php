<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PersonalAccessTokenResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Auth\AuthorizeService;
use Illuminate\Http\Request;
use Validator;

class AuthController extends Controller
{
    public $successStatus = 200;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();

        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);
        $user->generateApiToken();

        return response(new UserResource($user), 201);
    }

    /**
     * Description
     * @param Request $request
     * @return type
     */
    public function authenticate(Request $request)
    {
        $user = (new AuthorizeService())->authenticate($request->api_id, $request->api_token);

        if ($user) {
            $token = $user->createToken(env('APP_NAME'), ['*']);

            return response()->json(new PersonalAccessTokenResource($token), 201);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
