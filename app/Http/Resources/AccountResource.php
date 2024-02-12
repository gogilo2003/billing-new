<?php

namespace App\Http\Resources;

use App\Http\Resources\InvoiceResource;
use App\Http\Resources\TransactionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
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
        $client = $this->relationLoaded('client') ? new ClientResource($this->client) : null;
        $invoices = $this->relationLoaded('invoices') ? InvoiceResource::collection($this->invoices) : null;
        $transactions = $this->relationLoaded('transactions') ? TransactionResource::collection($this->transactions) : null;
        $res = [
            'id' => $this->id,
            'name' => $this->name,
            'credit' => $this->cr,
            'debit' => $this->dr,
            'balance' => $this->balance,
        ];

        if ($client) {
            $res['client'] = $client;
        }

        if ($invoices) {
            $res['invoices'] = $invoices;
        }

        if ($transactions) {
            $res['transactions'] = $transactions;
        }

        return $res;
    }
}
