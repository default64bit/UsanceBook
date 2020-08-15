<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::group(['middleware'=>['guest'], 'namespace'=>'Auth'], function(){
    Route::post('register','RegisterController');
    Route::post('login','LoginController');
    Route::post('refresh_token','RefreshTokenController');
});

Route::get('/purge', function(){
    Artisan::call('passport:purge');
});

Route::get('/{path?}', function(){
    return view('app');
})->where('path','.*');
