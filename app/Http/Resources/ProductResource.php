<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "name"=> $this->name,
            "details"=> $this->details,
            "image"=> $this->image,
            "price"=> $this->restaurant_id,
            "offer_price"=> $this->cost,
            "prepaire_time"=> $this->prepaire_time,
            "restaurant_id"=> $this->restaurant_id,
         



        ];
    }
}
