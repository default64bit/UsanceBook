<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware'=>['guest'], 'namespace'=>'Api'], function(){
    Route::group(['namespace'=>'v1', 'prefix'=>'v1'], function(){
        
    });
});

Route::group(['middleware'=>['auth:api'], 'namespace'=>'Api'], function(){
    Route::group(['namespace'=>'v1', 'prefix'=>'v1'], function(){
        Route::get('user','UserController@get_user');
        Route::post('logout','UserController@logout');

        Route::apiResource('transaction','TransactionController');
    });
});
