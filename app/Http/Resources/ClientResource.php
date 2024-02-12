<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => clean_isdns($this->phone),
            'email' => $this->email,
            'box_no' => $this->box_no,
            'post_code' => $this->post_code,
            'town' => $this->town,
            'postal_address' => 'P.O. Box ' . $this->box_no . '-' . $this->post_code . ', ' . $this->town,
            'address' => $this->address,
            'balance' => $this->balance,
            "accounts" => $this->relationLoaded('accounts') ? AccountResource::collection($this->accounts) : AccountResource::collection([]),
        ];
    }
}
