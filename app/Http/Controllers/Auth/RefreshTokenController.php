<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class RefreshTokenController extends Controller
{

    public function __invoke(Request $request){
        if(isset($_COOKIE['refresh_token']) && !empty($_COOKIE['refresh_token'])){
            $refresh_token = $_COOKIE['refresh_token'];
        }else{ abort(403); }

        $request->request->add([
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh_token,
            'client_id' => '1',
            // 'client_secret' => '3gT2zDT5iVBm338IGoB6YHYTOpQreTnr3y3tEDGU',
        ]);
        $tokenRequest = Request::create('/oauth/token','post');
        $response = Route::dispatch($tokenRequest);

        $content = json_decode($response->getContent(),true);
        return response()->json($content,$response->getStatusCode());
    }

}
