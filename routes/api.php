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

        Route::get('transaction/top_info','TransactionController@get_top_info');
        Route::apiResource('transaction','TransactionController');

        Route::apiResource('friends','FriendsController');

        Route::apiResource('cards','CardsController');

        Route::apiResource('groups','GroupsController');
    });
});
