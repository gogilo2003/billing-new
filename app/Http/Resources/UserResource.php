<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
        // return parent::toArray($request);
        return [
            "id" => $this->id,
            "name" => $this->name,
            "phone" => $this->phone,
            "email" => $this->email,
            "box_no" => $this->box_no,
            "post_code" => $this->post_code,
            "town" => $this->town,
            "address" => $this->address,
            "notification" => $this->notification,
            "balance" => $this->balance,
            "latest_cr_date" => date_create($this->latest_cr_date)->format('j-M-Y h:i:s A'),
            "latest_dr_date" => date_create($this->latest_dr_date)->format('j-M-Y h:i:s A'),
            "latest_transaction_date" => date_create($this->latest_transaction_date)->format('j-M-Y h:i:s A'),
            "created_at" => date_create($this->created_at)->format('j-M-Y h:i:s A'),
        ];
    }
}
