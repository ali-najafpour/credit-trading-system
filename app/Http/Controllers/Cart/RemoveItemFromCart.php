<?php

namespace App\Http\Controllers\Cart;

use DB;
use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use App\Services\Responser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Cart\CartResource;

class RemoveItemFromCart extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Product $product)
    {
        $user = auth()->user();

        $cart = Cart::firstOrCreate(
            [
                'user_id' =>  $user->id,
                'status' => "pending",
            ]);

        $item = CartItem::query()
            ->where('cart_id',$cart->id)
            ->where('product_id', $product->id)
            ->first();

        if($item){
            $item->delete();

            $cart->load('user','cartItems','cartItems.product');

            return Responser::success([
                null,
                null,
                CartResource::make($cart),
            ]);

        }else{
            return Responser::error(['Error' => 'Item not found in cart.']);
        }

    }
}
