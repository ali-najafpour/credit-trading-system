<?php

namespace App\Http\Controllers\Product\Admin;

use App\Models\Product;
use App\Services\Responser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Notification\SendNotificationJob;
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

        // Send notification to managers:
        $message = trans('messages.notifications.product.store');
        $notif_data = ProductResource::make($product)->resolve();

        SendNotificationJob::dispatch($message, null, 'manager', 'mail', $notif_data)->onQueue('notifications');

        return Responser::success(null, null, ProductResource::make($product));
    }
}
