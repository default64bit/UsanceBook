<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function get_user(Request $request){
        $user = $request->user();
        return response()->json($user);
    }

    public function logout(Request $request){
        $user = $request->user();

        $token = $user->token();
        $tokenRepository = app('Laravel\Passport\TokenRepository');
        $refreshTokenRepository = app('Laravel\Passport\RefreshTokenRepository');
        $tokenRepository->revokeAccessToken($token);
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token);

        // $token->revoke();
        // $token->delete();

        $cookie = Cookie::forget('refresh_token');

        $this->clear_oauth_tables($user);
        
        return response('',200)->withCookie($cookie);
    }
}
