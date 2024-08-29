<?php

namespace App\Traits;

use App\Models\Scopes\OrderByIdScope;

trait OrderByIdTrait
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function bootOrderByIdTrait()
    {
        static::addGlobalScope(new OrderByIdScope);
    }
}
