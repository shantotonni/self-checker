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
                    'id'=>$user->UserId,
                    'name'=>$user->UserName,
                    'username'=>$user->UserName,
                    'UserCode'=>$user->UserCode,
                    'role_id'=>$user->RoleId,
                    'role_name'=>isset($user->role) ? $user->role->RoleName : '',
                    'mobile'=>$user->MobileNo,
                    'email'=>$user->Email,
                    'Active'=>$user->IsActive,
                ];
            })
        ];
    }
}
