<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class QuotationItemResource extends JsonResource
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
            "id" => $this->id,
            "quotation" => $this->relationLoaded('quotation')
                ? new QuotationResource($this->quotation)
                : $this->quotation_id,
            "particulars" => $this->particulars,
            "quantity" => $this->quantity,
            "price" => $this->price,
            "unit" => $this->unit,
            "amount" => $this->quantity * $this->price,
            "created_at" => date_create($this->created_at)->format('j-M-Y h:i:s A'),
        ];
    }
}
/*
"id": 2,
"quotation_id": 1,
"particulars": "Domain Registration",
"quantity": 1,
"price": "1500.00",
"unit": "year",
"created_at": "2022-06-23T02:42:00.000000Z",
"updated_at": "2022-06-23T03:35:33.000000Z"
*/
