<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    private $user;

    public function __construct(UserRepositoryInterface $userRepository){
        $this->user = $userRepository;
    }

    public function __invoke(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|max:255|bail|unique:users,email',
            'password' => 'required|min:8|string',
        ]);
        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()->first(),
            ],422);
        }

        $this->user->create([
            'avatar' => null,
            'name' => $request->name, 'family' => $request->family,
            'email' => $request->email, 'phone' => null,
            'password' => $request->password,
        ]);

        $request->request->add([
            'grant_type' => 'password',
            'username' => $request->email,
            'password' => $request->password,
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
    }

}
