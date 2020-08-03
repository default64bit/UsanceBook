<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    // return view('welcome');

    function test($array){
        foreach($array as $item){
            yield $item;
        }
    }

    foreach(test([1,2,3]) as $item){
        dump($item);
    }

});
