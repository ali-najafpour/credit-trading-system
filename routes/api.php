<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('xss')->group(function () {

    Route::namespace('Auth')->group(function () {
        Route::post('auth/login', 'Login');
    });

    Route::namespace('Product')->group(function () {
        Route::get('product/list', 'IndexProduct');
    });

    Route::middleware('auth:api')->group(function () {
        Route::namespace('Auth')->group(function () {
            Route::get('auth/my-profile', 'ShowMyProfile');
        });
        Route::namespace('Cart')->group(function () {
            Route::get('cart/my-cart', 'ShowMyCart');
            Route::get('cart/update-my-cart', 'UpdateMyCart');
            Route::get('cart/add-to-cart/{product}/{count?}', 'AddToCart');
            Route::get('cart/reduce-from-cart/{product}/{count?}', 'ReduceFromCart');
            Route::delete('cart/remove-from-cart/{product}', 'RemoveItemFromCart');
        });

        Route::middleware('role:manager,admin')->group(function () {
            Route::namespace('Product\Admin')->group(function () {
                Route::post('product/admin/add', 'StoreProduct');
            });
        });

        Route::middleware('role:manager')->group(function () {
            //
        });


    });

});


