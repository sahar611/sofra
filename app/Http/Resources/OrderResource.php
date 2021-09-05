<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            "address"=> $this->address,
            "status"=> $this->status,
            "client_id"=> $this->client_id,
            "restaurant_id"=> $this->restaurant_id,
            "cost"=> $this->cost,
            "delivery_cost"=> $this->delivery_cost,
            "total"=> $this->total,
            "commission"=> $this->commission,
            "notes"=> $this->notes,



        ];
    }
}
