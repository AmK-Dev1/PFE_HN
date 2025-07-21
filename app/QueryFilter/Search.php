<?php

namespace App\QueryFilter;

use App\Models\ActionType;
use App\Models\Company;
use App\Models\User;
use App\Models\Admin;
use Spatie\Permission\Models\Role;

class Search extends Filter
{

    protected function applyFilters($builder)
    {
        $q = request($this->filterName());

        if (empty($q)) {
            return $builder;
        }
        $model = $builder->getModel();

        if (is_array($q)) {
            return $builder;
        }

        if ($model instanceof Admin) {
            $builder->where('name', 'like', '%' . $q . '%')
                ->orWhere('email', 'like', '%' . $q . '%');
        }

        if ($model instanceof Role) {
            $builder->where('name', 'like', '%' . $q . '%');
        }

        if ($model instanceof Company) {
            $builder->where('name', 'like', '%' . $q . '%')
                ->orWhere('description', 'like', '%' . $q . '%');
        }

        if ($model instanceof User) {
            $builder->where('name', 'like', '%' . $q . '%')
                ->orWhere('phone', 'like', '%' . $q . '%');
        }

        return $builder;
    }
}
