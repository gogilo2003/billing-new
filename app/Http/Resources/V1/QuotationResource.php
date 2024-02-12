<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\UserResource;
use App\Http\Resources\ClientResource;
use Illuminate\Http\Resources\Json\JsonResource;

class QuotationResource extends JsonResource
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
        $amount = 0;

        if ($this->relationLoaded('items')) {
            foreach ($this->items as $item) {
                $amount += ($item->quantity * $item->price);
            }
        }
        return [
            "id" => $this->id,
            "description" => $this->description,
            "validity" => $this->validity,
            "amount" => $amount,
            "client" => $this->relationLoaded('client')
                ? new ClientResource($this->client)
                : $this->client_id,
            "user" => $this->relationLoaded('user')
                ? new UserResource($this->user)
                : $this->user_id,
            "items" => $this->relationLoaded('items')
                ? QuotationItemResource::collection($this->items)
                : QuotationItemResource::collection([]),
            "barcode" => $this->barcode,
            "created_at" => date_create($this->created_at)
                ->format('j-M-Y h:i:s A'),
        ];
    }
}
/*
"user_id": 1,
    "created_at": "2022-06-23T02:42:00.000000Z",
    "updated_at": "2022-06-23T02:42:00.000000Z",
*/
