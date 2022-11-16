<?php

namespace App\Http\Controllers\Api\Auth;

use App\Constants\StatusCodes;
use JWTAuth;
use App\Http\Controllers\Base\BaseRestController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;


class AuthenticationController extends BaseRestController
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        //valid credential
        $validator = Validator::make($credentials, [
            'email'    => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);
        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], StatusCodes::HTTP_BAD_REQUEST);
        }
        //Request is validated
        //Crean token
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
            return $credentials;

            return response()->json([
                'success' => false,
                'message' => 'Could not create token.',
            ], 500);
        }
        $user = User::findOneByEmail($credentials['email']);
        return $this->respondWithToken($token);
    }


    public function refresh(Request $request) {

        return $this->respondWithToken(Auth::refresh());
    }

    public function me(Request $request) {
        return response()->json(Auth::user());
    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'success' => true,
        ]);
    }
}
