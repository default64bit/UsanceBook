<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('oauth/authorize',[
    'uses' => '\Laravel\Passport\Http\Controllers\AuthorizationController@authorize',
    'as' => 'passport.authorizations.authorize'
])->middleware('auth:web');
Route::post('oauth/authorize',[
    'uses' => '\Laravel\Passport\Http\Controllers\ApproveAuthorizationController@approve',
    'as' => 'passport.authorizations.approve'
])->middleware('auth:web');

Route::view('login','login')->name('login');
Route::group(['namespace'=>'Auth'],function(){
    Route::post('login','LoginController');
    Route::post('refresh_token','RefreshTokenController');
});

Route::get('/make_user',function(){
    User::create([
        'name' => 'kasra',
        'family' => 'keshvardoost',
        'email' => 'kasrakeshvardoost@gmail.com',
        'password' => bcrypt('12345678'),
    ]);
});

Route::get('/purge',function(){
    Artisan::call('passport:purge');
});

Route::get('/{path?}',function(){
    return view('app');
})->where('path','.*');
