<?php

namespace App\Traits;

use App\Models\Scopes\OrderByIdScope;

trait BasicModelTrait
{
    private $pageSizeLimit = 100;

    public function getPerPage()
    {
        $pageSize = (int)request('per_page', $this->perPage);
        return min($pageSize, $this->pageSizeLimit);
    }

    public function attributeExists($attr)
    {
        return array_key_exists($attr, $this->attributes);
    }
}
