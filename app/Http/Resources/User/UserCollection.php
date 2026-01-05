<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($user){
                return [
                    'id'=>$user->id,
                    'name'=>$user->name,
                    'name_bn'=>$user->name_bn,
                    'username'=>$user->username,
                    'role_id'=>$user->role_id,
                    'role_name'=>isset($user->role) ? $user->role->name : '',
                    'designation'=>$user->designation,
                    'mobile'=>$user->mobile,
                    'mobile_bn'=>$user->mobile_bn,
                    'address'=>$user->address,
                    'email'=>$user->email,
                    'Active'=>$user->Active,
                    'device_token'=>$user->device_token,
                    'image'=>$user->image,
                    'user_image'=>url('/').'/images/user/'.$user->image,
                ];
            })
        ];
    }
}
