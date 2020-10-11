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
        
        Route::get('users_list','FriendsController@get_users_list');
        Route::get('friends/info','FriendsController@friends_count_info');
        Route::get('friends/request_list','FriendsController@friend_request_list');
        Route::post('friends/request/{email}/accept','FriendsController@accept_friend_request');
        Route::post('friends/request/{email}/reject','FriendsController@reject_friend_request');
        Route::apiResource('friends','FriendsController');
        
        Route::apiResource('cards','CardsController');
        Route::post('cards/{id}/statistics','CardsController@statistics');
        
        Route::apiResource('groups','GroupsController');
        Route::post('groups/{id}/statistics','GroupsController@statistics');
    });
});
