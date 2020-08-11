<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class UserController extends Controller
{
    private $user;

    public function __construct(UserRepositoryInterface $userRepository){
        $this->user = $userRepository;
    }

    public function get_user(Request $request){
        $user = $request->user();
        $user_info = $this->user->userInfo($user->id);
        return response()->json($user_info);
    }

    public function logout(Request $request){
        $user = $request->user();

        $token = $user->token();
        $tokenRepository = app('Laravel\Passport\TokenRepository');
        $refreshTokenRepository = app('Laravel\Passport\RefreshTokenRepository');
        $tokenRepository->revokeAccessToken($token);
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token);

        $this->clear_oauth_tables($user);
        
        $cookie = Cookie::forget('refresh_token');
        return response('',200)->withCookie($cookie);
    }
}
