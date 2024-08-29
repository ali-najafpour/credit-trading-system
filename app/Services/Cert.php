<?php

namespace App\Services;


class Cert
{
    public function user()
    {
        if (auth()->check()) {
            return auth()->user();
        }
        return null;

    }
    public function check()
    {
        if (auth()->check()) {
            return true;
        }
        return false;
    }
    public function userId()
    {
        if (auth()->check()) {
            return auth()->user()->id;
        }
        return false;
    }
}
