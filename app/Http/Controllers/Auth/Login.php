<?php

namespace App\Http\Controllers\Auth;

use App\Services\Responser;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\User\UserResource;

class Login extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        $data = $request->validated();

        $credentials = $this->findCredentials($data);

        if (auth()->attempt($credentials)) {

            $user = auth()->user();

            // ==> Login with email amd password:
            if(array_key_exists('email',$credentials)) {
                if (! auth()->user()->isEmailVerified()) {
                    return Responser::error(['verification' => trans('messages.not_verified.email')]);
                }

            // ==> Login with cell_number amd password:
            }elseif(array_key_exists('cell_number',$credentials)){
                if (! auth()->user()->isCellNumberVerified()) {
                    return Responser::error(['verification' => trans('messages.not_verified.cell_number')]);
                }

            // ==> Login with username amd password:
            }elseif(array_key_exists('user_name',$credentials)){
                if (! auth()->user()->isVerified()) {
                    return Responser::error(['verification' => trans('messages.not_verified.both')]);
                }
                return Responser::error(['verification' => trans('messages.not_verified.both')]);
            }

            $token = auth()->user()->createToken('Personal Client')->plainTextToken;

            return Responser::data(
                ['token' => $token, 'user' => UserResource::make(auth()->user())]
            );


        }

        return Responser::error(['credentials' => trans('messages.credentials')]);

    }

    private function findCredentials($data)
    {
        if(array_key_exists('cell_number',$data)){
            $data['cell_number'] = ltrim($data['cell_number'],"0");
        }

        $credentials = [
            'password' => $data['password'],
        ];

        if(array_key_exists('email',$data)){
            $credentials = Arr::add($credentials, 'email', $data['email']);
        }elseif(array_key_exists('cell_number',$data)){
            $credentials = Arr::add($credentials, 'cell_number', $data['cell_number']);
        }elseif(array_key_exists('user_name',$data)){
            $credentials = Arr::add($credentials, 'user_name', $data['user_name']);
        }

        return $credentials;
    }

    private function generateToken($data)
    {

    }
}
