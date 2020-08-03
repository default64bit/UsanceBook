<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function(){
    function test($array){
        foreach($array as $item){
            yield $item;
        }
    }
    foreach(test([1,2,3]) as $item){
        dump($item);
    }
});

Route::get('/{path?}',function(){
    return view('app');
})->where('path','.*');
