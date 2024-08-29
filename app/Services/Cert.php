<?php

namespace App\Services;


class Cert
{
    public function user()
    {
        if (auth('api')->check()) {
            return auth('api')->user();
        }
        return null;

    }
    public function check()
    {
        if (auth('api')->check()) {
            return true;
        }
        return false;
    }
    public function userId()
    {
        if (auth('api')->check()) {
            return auth('api')->user()->id;
        }
        return false;
    }
    public function isAdmin()
    {
        if (auth('api')->check()) {
            return auth('api')->user()->hasRole('manager');
        }
        return false;
    }
}
