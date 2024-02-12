<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DomainResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'domain' => $this->domain,
            'registered_on' => $this->registered_on,
            'expires_on' => $this->expires_on,
            'remarks' => $this->remarks,
            'status' => $this->status,
            'client_id' => $this->client_id,
            'notify' => $this->notify,
        ];
    }
}
