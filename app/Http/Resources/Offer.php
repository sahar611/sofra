<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Offer extends JsonResource
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
            'name'=>$this->name,
            'details' => $this->details,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'image' => $this->image, 
            'restaurant' =>$this->restaurant->name,
            
            
                    ];
        //return parent::toArray($request);
    }
}
