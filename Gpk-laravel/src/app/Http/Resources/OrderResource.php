<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'user_id' => $this->user_id,
            'reservation_id' => $this->reservation_id,
            'service_id' => $this->service_id,
            'service' => [
                'id' => $this->service->id ?? null,
                'title' => $this->service->title ?? null,
            ],
            'price' => $this->price,
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
            'payment' => $this->payment,
            'payment_text' => $this->payment == 0 ? '現地払い' : 'クレジット',
            'payment_status' => $this->payment_status,
            'type' => $this->type,
            'type_text' => $this->type == 1 ? '事前予約' : '現地注文',
            'status' => $this->status,
            'status_text' => $this->status == 1 ? '有効' : '無効',
            'order_details' => OrderDetailResource::collection($this->whenLoaded('orderDetails')),
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}




















