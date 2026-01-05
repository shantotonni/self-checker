<?php

namespace App\Http\Resources\Showroom;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ShowroomCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($showroom){
                return [
                    'id' => $showroom->id,
                    'area_id' => $showroom->area_id,
                    'area_name' => isset($showroom->Area) ? $showroom->Area->areaname : '',
                    'area_name_bn' => isset($showroom->Area) ? $showroom->Area->name_bn : '',
                    'responsible_person' => $showroom->name,
                    'showroom_name' => $showroom->showroom_name,
                    'address' => $showroom->address,
                    'mobile' => $showroom->mobile_number,
                    'email' => $showroom->email,
                    'lat' => $showroom->lat,
                    'long' => $showroom->lon,
                    'image' => $showroom->image,
                    'showroom_image'=>url('/').'/images/showroom/'.$showroom->image,
                ];
            })
        ];
    }
}
