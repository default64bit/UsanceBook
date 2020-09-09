<?php

namespace App\Filters;

class GroupFilter extends Filter
{
    public $filter_param = 'group';

    public function filter($builder){
        return $builder->whereHas('groups',function($query){
            $query->where('group_id',request($this->filter_param));
        });
    }
}