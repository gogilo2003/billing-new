<?php

namespace App\Http\Resources;

use App\Models\Client;
use Illuminate\Http\Resources\Json\JsonResource;

class SelectClientResource extends JsonResource
{

    protected $model = Client::class;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        $accounts = $this->relationLoaded('accounts') ? SelectAccountResource::collection($this->accounts) : null;

        $clients = [
            'id' => $this->id,
            'name' => strtoupper($this->name),
        ];

        if ($accounts) {
            $clients['accounts'] = $accounts;
        }

        return $clients;
    }
}
