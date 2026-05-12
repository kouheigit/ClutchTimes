<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddOrderResource extends JsonResource
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
            'user' => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? null,
                'email' => $this->user->email ?? null,
            ],
            'reservation' => [
                'id' => $this->reservation->id ?? null,
                'checkin_date' => $this->reservation->checkin_date ? $this->reservation->checkin_date->format('Y-m-d') : null,
                'checkout_date' => $this->reservation->checkout_date ? $this->reservation->checkout_date->format('Y-m-d') : null,
            ],
            'total_price' => $this->total_price,
            'payment' => $this->payment,
            'payment_text' => $this->payment == 0 ? '現地払い' : 'クレジット',
            'payment_status' => $this->payment_status,
            'payment_status_text' => $this->payment_status == 0 ? '未払い' : '支払済み',
            'status' => $this->status,
            'status_text' => $this->status == 1 ? '有効' : '無効',
            'add_order_details' => AddOrderDetailResource::collection($this->whenLoaded('addOrderDetails')),
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}

