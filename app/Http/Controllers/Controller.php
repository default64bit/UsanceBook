<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function clear_oauth_tables(User $user){
        $revoked_access_token_list = [];
        
        $revoked_access_tokens = DB::table('oauth_access_tokens')->where('user_id',$user->id)->where('revoked',1);
        // $revoked_oauth_access_tokens = $revoked_access_tokens->get();
        // foreach($revoked_oauth_access_tokens as $oauth_access_token){
        //     $revoked_access_token_list[] = $oauth_access_token;
        // }

        // DB::table('oauth_refresh_tokens')->whereIn('access_token_id',$revoked_access_token_list)->delete();
        $revoked_access_tokens->delete();
        DB::table('oauth_auth_codes')->where('revoked',1)->delete();
        DB::table('oauth_refresh_tokens')->where('revoked',1)->delete();
    }

    protected function _setHttpOnlyCookie(string $name, string $value, int $time){
        cookie()->queue(
            $name,$value,$time,
            null,null,false,
            true // httponly
        );
    }

}
