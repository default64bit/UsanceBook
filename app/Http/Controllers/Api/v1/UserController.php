<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function get_user(Request $request){
        $user = $request->user();
        return response()->json($user);
    }

    public function logout(Request $request){
        $user = $request->user();

        $user->token()->revoke();
        $user->token()->delete();

        $access_tokens = DB::table('oauth_access_tokens')->where('user_id',$user->id)->where('client_id',1);
        $oauth_access_tokens = $access_tokens->get();
        foreach($oauth_access_tokens as $oauth_access_token){
            DB::table('oauth_refresh_tokens')->where('access_token_id',$oauth_access_token->id)->delete();
        }
        $access_tokens->delete();
        DB::table('oauth_auth_codes')->where('revoked',1)->delete();
        
        // return response('',200);
    }
}
