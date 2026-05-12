<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'cart_id' => $this->cart_id,
            'service_id' => $this->service_id,
            'service_option_id' => $this->service_option_id,
            'service' => [
                'id' => $this->service->id ?? null,
                'title' => $this->service->title ?? null,
                'price' => $this->service->price ?? null,
            ],
            'service_option' => [
                'id' => $this->serviceOption->id ?? null,
                'title' => $this->serviceOption->title ?? null,
                'price' => $this->serviceOption->price ?? null,
            ],
            'price' => $this->price,
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}



















