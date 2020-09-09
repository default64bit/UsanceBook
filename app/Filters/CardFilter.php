<?php

namespace App\Filters;

class CardFilter extends Filter
{
    public $filter_param = 'card';

    public function filter($builder){
        return $builder->where('card_id',request($this->filter_param));
    }
}