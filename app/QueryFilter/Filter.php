<?php


namespace App\QueryFilter;

use Closure;
use Illuminate\Support\Str;

abstract class Filter
{
    public function handle($request, Closure $next)
    {
        if(str_starts_with($this->filterName(), 'sort_by')){
            return $this->applyFilters($next($request));
        }

        if(str_starts_with($this->filterName(), 'order_by')){
            return $this->applyFilters($next($request));
        }

        if(str_starts_with($this->filterName(), 'having')){
            return $this->applyFilters($next($request));
        }

        if( ! request()->has($this->filterName())){
            return $next($request);
        }

        return $next($request)->where(function($q)
        {
            $this->applyFilters($q);
        });
    }

    abstract protected function applyFilters($builder);

    protected function filterName(): string
    {
        return Str::snake(class_basename($this));
    }
}
