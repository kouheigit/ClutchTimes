<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'hotel_id' => $this->hotel_id,
            'hotel' => [
                'id' => $this->hotel->id ?? null,
                'name' => $this->hotel->name ?? null,
            ],
            'title' => $this->title,
            'body' => $this->body,
            'price' => $this->price,
            'stock' => $this->stock,
            'minimum' => $this->minimum,
            'unit' => $this->unit,
            'tab' => $this->tab,
            'tab_text' => $this->tab == 1 ? '事前予約' : '現地注文',
            'sort' => $this->sort,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'status' => $this->status,
            'status_text' => $this->status == 1 ? '有効' : '無効',
            'service_options' => ServiceOptionResource::collection($this->whenLoaded('serviceOptions')),
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}




















