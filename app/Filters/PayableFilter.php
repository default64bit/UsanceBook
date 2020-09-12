<?php

namespace App\Filters;

class PayableFilter extends Filter
{
    public $filter_param = 'payable';

    public function filter($builder){
        // return $builder->whereHas('user_id',request($this->filter_param));
    }
}