<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    public function __invoke(Request $request){
        // TODO
        // somehow prevent the "Aprove Page" from loading and get the code automaticaly and send it to the callback url

        // TIP
        // make a POST request to route('passport.authorizations.approve')
        // "state" value="{{ $request->state }}"
        // "client_id" value="{{ $client->id }}"
        // "auth_token" value="{{ $authToken }}"

        // TIP
        // make a custome authorize.blade.php view that automaticaly submit the aprove form
        
        // TODO 2
        // whe should save the tokens ourselfs so we can make it http only

        // Validator::make($request->all(),[
        //     'email' => 'required|email|max:255',
        //     'password' => 'required|min:8|string',
        // ]);
        // $user = User::where('email',$request->email)->first();
        // if($user){
        //     $authrize = Hash::check($request->password,$user->password);
        //     if($authrize){ return redirect()->intended(); }
        // }
        
        $authrize = auth()->attempt($request->only('email','password'));
        if($authrize){ return redirect()->intended(); }
        
        return redirect('login?error=1');
    }

}
