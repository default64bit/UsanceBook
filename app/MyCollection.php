<?php

namespace App;

use ArrayIterator;
use IteratorAggregate;

class MyCollection implements IteratorAggregate
{
    private $items = [];
    
    public function __construct($items = []){
        $this->items = $items;
    }

    public function first(){
        return isset($this->items[0]) ? $this->items[0] : null;
    }

    public function count(){
        return count($this->items);
    }

    public function getIterator(){
        return new ArrayIterator($this->items);
    }

}