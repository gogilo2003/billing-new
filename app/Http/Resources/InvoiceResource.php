<?php

namespace App\Http\Resources;

use App\Http\Resources\AccountResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
        $items = $this->relationLoaded('items') ? InvoiceDetailResource::collection($this->items) : null;
        $amount = 0.00;

        foreach ($this->items as $item) {
            $amount += ($item->quantity * $item->price);
        };

        $account = $this->relationLoaded('account') ? new AccountResource($this->account) : $this->account_id;

        $res = [
            'id' => $this->id,
            'name' => $this->name,
            'amount' => number_format($amount, 2),
            'barcode' => $this->barcode,
            'created_at' => $this->created_at->format('D, j-M-Y'),
            'account_id' => $this->account_id,
        ];

        if ($account) {
            $res['account'] = $account;
        }

        if ($items) {
            $res['items'] = $items;
        }

        return $res;
    }
}
