<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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

        $category = new ProductCategoryResource($this->whenLoaded('category'));

        return [
            "id" => $this->id,
            "code" => $this->code,
            "name" => $this->name,
            "price" => $this->price,
            "unit" => $this->unit,
            "description" => $this->description,
            "category"=>$category,
        ];
    }
}
