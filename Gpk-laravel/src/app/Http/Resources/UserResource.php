<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Consts\UserConst;

class UserResource extends JsonResource
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
            'member_id' => $this->member_id,
            'name' => $this->name,
            'email' => $this->email,
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'last_kana' => $this->last_kana,
            'first_kana' => $this->first_kana,
            'zip1' => $this->zip1,
            'zip2' => $this->zip2,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'tel' => $this->tel,
            'type' => $this->type,
            'type_text' => UserConst::TYPE_LIST[$this->type] ?? '',
            'status' => $this->status,
            'status_text' => $this->status == 1 ? '有効' : '無効',
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}




















