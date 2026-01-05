<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'=>$this->id,
            'name'=>$this->name,
            'name_bn'=>$this->name_bn,
            'username'=>$this->username,
            'role_id'=>$this->role_id,
            'role_name'=>isset($this->role) ? $this->role->Name : '',
            'designation'=>$this->designation,
            'mobile'=>$this->mobile,
            'mobile_bn'=>$this->mobile_bn,
            'address'=>$this->address,
            'email'=>$this->email,
            'Active'=>$this->Active,
            'image'=>$this->image,
            'device_token'=>$this->device_token,
        ];
    }
}
