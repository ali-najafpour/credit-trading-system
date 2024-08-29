<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->when(!is_null($this->first_name), mb_strlen($this->first_name, 'utf-8')),
            'is_active' => $this->isActive(),
            'visible_in_store' => $this->isVisible(),
            'total_count' => $this->total_count,
            'creator' => UserResource::make($this->whenLoaded('creator')),
        ];
    }
}
