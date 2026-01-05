<?php

namespace App\Http\Resources\Dealer;

use Illuminate\Http\Resources\Json\JsonResource;

class DealerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'area_id' => $this->area_id,
            'area_name' => isset($this->Area) ? $this->Area->areaname : '',
            'area_name_bn' => isset($this->Area) ? $this->Area->name_bn : '',
            'dealer_name' => $this->responsible_person,
            'address' => $this->address,
            'mobile' => $this->mobile,
            'lat' => $this->lat,
            'long' => $this->lon,
            'image' => $this->image,
            'mobile_image' => url('/').'/images/dealer/'. $this->image
        ];
    }
}
