<?php

namespace App\Filters;

use Closure;

abstract class Filter
{

    public function handle($request, Closure $next){
        if(!request()->has($this->filter_param)){
            return $next($request);
        }

        $builder = $next($request);
        return $this->filter($builder);
    }

    public abstract function filter($builder);

}