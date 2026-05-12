<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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
            'order_id' => $this->order_id,
            'service_id' => $this->service_id,
            'service_option_id' => $this->service_option_id,
            'service' => [
                'id' => $this->service->id ?? null,
                'title' => $this->service->title ?? null,
            ],
            'service_option' => [
                'id' => $this->serviceOption->id ?? null,
                'title' => $this->serviceOption->title ?? null,
            ],
            'price' => $this->price,
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
        ];
    }
}




















