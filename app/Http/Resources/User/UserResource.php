<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if($this->last_name){
            $name = $this->first_name . ' ' . $this->last_name;
        }else{
            $name = $this->first_name;
        }

        return [
            'id' => $this->id,
            'name' => $name,
            'first_name' => $this->first_name,
            'first_name_length' => $this->when(!is_null($this->first_name), mb_strlen($this->first_name, 'utf-8')),
            'last_name' => $this->last_name,
            'username' => $this->user_name,
            'cell_number' => $this->country_code,
            'cell_number_verified_at' => $this->isCellNumberVerified(),
            'email' => $this->email,
            'email_verified_at' => $this->isEmailVerified(),
            'role' => $this->role,
        ];
    }
}
