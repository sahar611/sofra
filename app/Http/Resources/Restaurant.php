<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Restaurant extends JsonResource
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
'phone' => $this->phone,
'email' => $this->email,
'phone' => $this->phone,
'image' => $this->image,

'region' => $this->region->name,

 'categories' =>Categories::collection($this->whenLoaded('categories')),


        ];
        // return parent::toArray($request);
    }
}
