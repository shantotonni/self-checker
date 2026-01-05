<?php

namespace App\Http\Resources\Showroom;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowroomResource extends JsonResource
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
            'responsible_person' => $this->name,
            'showroom_name' => $this->showroom_name,
            'email' => $this->email,
            'address' => $this->address,
            'mobile' => $this->mobile_number,
            'lat' => $this->lat,
            'long' => $this->lon,
            'image' => $this->image,
             'showroom_image'=>url('/').'/images/showroom/'.$this->image,
        ];
    }
}
