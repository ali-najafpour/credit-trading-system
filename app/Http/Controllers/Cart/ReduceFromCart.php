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

class ReduceFromCart extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Product $product, int $count = 1)
    {
        if($count < 1){
            return Responser::error(['Error' => 'Invalid count.']);
        }

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
            // Check item count + $count with updated product total count:
            if($product->total_count < $item->count - $count){
                $item->count = $product->total_count;
                $item->save();

                $cart->load('user','cartItems','cartItems.product');

                return Responser::success([
                    'Warning',
                    'The quantity of this item has updated in your cart.',
                    CartResource::make($cart),
                ]);

            }else{
                $item->count -= $count;
                $item->save();

                $cart->load('user','cartItems','cartItems.product');

                return Responser::success([
                    null,
                    null,
                    CartResource::make($cart),
                ]);
            }

        }else{
            return Responser::error(['Error' => 'Item not found in cart.']);
        }

    }
}
