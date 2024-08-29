<?php

namespace App\Http\Controllers\Product\Admin;

use App\Models\Product;
use App\Services\Responser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;
use App\Http\Requests\Product\StoreProductRequest;

class StoreProduct extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreProductRequest $request)
    {
        $data = $request->validated();

        $product = Product::create($data);

        $product = Product::query()->with('creator')->find($product->id);

        // notification to manager| mail
        return Responser::success(null, null, ProductResource::make($product));
    }
}
