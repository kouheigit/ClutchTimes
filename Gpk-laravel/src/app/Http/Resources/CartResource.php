<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $cartDetails = $this->whenLoaded('cartDetails');
        $totalPrice = $cartDetails ? $cartDetails->sum('total_price') : 0;
        
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user' => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? null,
                'email' => $this->user->email ?? null,
            ],
            'cart_details' => CartDetailResource::collection($this->whenLoaded('cartDetails')),
            'items' => CartDetailResource::collection($this->whenLoaded('cartDetails')), // 後方互換性のため
            'items_count' => $cartDetails ? $cartDetails->count() : 0,
            'total_price' => $totalPrice,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}


