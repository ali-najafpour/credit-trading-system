<?php

namespace App\Http\Resources\Cart;

use Illuminate\Http\Request;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => UserResource::make($this->whenLoaded('creator')),
            'items' => CartItemResource::collection($this->whenLoaded('cartItems')),
            'status' => $this->status,
        ];
    }
}
