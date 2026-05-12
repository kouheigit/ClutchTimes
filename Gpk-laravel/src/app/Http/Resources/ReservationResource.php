<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Consts\ReservationConst;

class ReservationResource extends JsonResource
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
            'hotel' => [
                'id' => $this->hotel->id ?? null,
                'name' => $this->hotel->name ?? null,
            ],
            'user' => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? null,
                'email' => $this->user->email ?? null,
            ],
            'checkin_date' => $this->checkin_date ? $this->checkin_date->format('Y-m-d') : null,
            'checkout_date' => $this->checkout_date ? $this->checkout_date->format('Y-m-d') : null,
            'checkin_time' => $this->checkin_time ? $this->checkin_time->format('H:i') : null,
            'checkout_time' => $this->checkout_time ? $this->checkout_time->format('H:i') : null,
            'days' => $this->days,
            'guests' => [
                'adult' => $this->adult,
                'child' => $this->child,
                'dog' => $this->dog,
            ],
            'name' => $this->name,
            'note' => $this->note,
            'room_key' => $this->room_key,
            'payment' => $this->payment,
            'payment_text' => $this->payment == 0 ? '現地払い' : 'クレジット',
            'status' => $this->status,
            'status_text' => ReservationConst::STATUS_LIST[$this->status] ?? '',
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
            'total_price' => $this->whenLoaded('orders', function () {
                return $this->orders->sum('total_price');
            }),
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}




















