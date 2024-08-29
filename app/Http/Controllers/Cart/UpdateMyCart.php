<?php

namespace App\Http\Controllers\Cart;

use DB;
use App\Models\Cart;
use App\Services\Responser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Cart\CartResource;

class UpdateMyCart extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $requestt)
    {

        $user = auth()->user();

        $cart = Cart::firstOrCreate(
            [
                'user_id' =>  $user->id,
                'status' => "pending",
            ]);

        $cart->load('user','cartItems','cartItems.product');

        $items = $cart->cartItems;

        foreach($items as $item){
            $product = $item->product;

            if($product->total_count < $item->count){
                $item->count = $product->total_count;
                $item->save();
            }
        }

        return Responser::success([
            null,
            null,
            CartResource::make($cart),
        ]);

    }
}
