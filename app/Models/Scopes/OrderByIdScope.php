<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OrderByIdScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (in_array('sort', $model->getFillable())) {
            $builder->orderBy($model->getTable().'.sort', 'desc')->orderByDesc($model->getTable().'.id');
        } else {
            $builder->orderBy($model->getTable().'.id', 'desc');
        }
    }
}
