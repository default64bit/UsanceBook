<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class RefreshTokenController extends Controller
{

    public function __invoke(Request $request){
        $refresh_token = $request->cookie('refresh_token');
        abort_unless($refresh_token, 403, 'Your refresh token is expired.');
        // if(isset($_COOKIE['refresh_token']) && !empty($_COOKIE['refresh_token'])){
        //     $refresh_token = $_COOKIE['refresh_token'];
        // }else{ abort(403); }

        $request->request->add([
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh_token,
            'client_id' => config('auth.LOCAL_API_CLIENT_ID'),
            'client_secret' => config('auth.LOCAL_API_SECRET'),
        ]);
        $tokenRequest = Request::create('/oauth/token','post');
        $response = Route::dispatch($tokenRequest);

        if($response->getStatusCode() == 200){
            $content = json_decode($response->getContent());
            $this->_setHttpOnlyCookie('refresh_token',$content->refresh_token,5*24*60);
            return response()->json([
                'access_token' => $content->access_token,
                'token_type' => $content->token_type,
                'expires_in' => $content->expires_in,
            ],$response->getStatusCode());
        }

        $content = json_decode($response->getContent(),true);
        return response()->json($content,401);
    }

}
