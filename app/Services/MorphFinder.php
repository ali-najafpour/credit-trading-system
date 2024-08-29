<?php

namespace App\Services;

use ReflectionClass;
use Illuminate\Support\Facades\Validator;

class MorphFinder
{

    public static function find($interface)
    {

        Validator::make(request()->all(), [
            'rel_id' => 'required',
            'rel_type' => 'required',
        ])->validate();

        $classNameSpace = config('morphmap.' . request('rel_type'));

        if (!class_exists($classNameSpace)) {
            abort(404, 'rel not found');
        }

        $class = new ReflectionClass($classNameSpace);
        if (!$class->implementsInterface($interface)) {
            abort(404, 'commentable not found');
        }

        return $classNameSpace::findOrFail(request('rel_id'));
    }
}
