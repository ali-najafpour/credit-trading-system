<?php

namespace App\Http\Controllers\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;

class IndexProduct extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $products = Product::query()
            ->filtered()
            ->isVisible()
            ->isActive()
            // ->with('creator')
            ->paginate();

        return ProductResource::collection($products);
    }
}
