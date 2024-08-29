<?php

namespace App\Http\Controllers\Cart;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Cart\CartResource;

class ShowMyCart extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = auth()->user();

        $cart = Cart::firstOrCreate(
            [
                'user_id' =>  $user->id,
                'status' => "pending",
            ]);

        $cart->load('user','cartItems','cartItems.product');

        return CartResource::make($cart);
    }
}
