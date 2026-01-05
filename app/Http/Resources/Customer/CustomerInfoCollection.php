<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerInfoCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'mobile' => $customer->mobile,
                    'product_id' => $customer->product_id,
                    'product_name' => isset($customer->Products) ? $customer->Products->name : '',
                    'product_name_bn' => isset($customer->Products) ? $customer->Products->name_bn : '',
                    'model' => isset($customer->chassis_one) ? $customer->chassis_one->model: '',
                    'district_id' => $customer->district_id,
                    'district_name' => isset($customer->District) ? $customer->District->name : '',
                    'district_name_bn' => isset($customer->District) ? $customer->District->name_bn : '',
                    'address' => $customer->address,
                    'chassis' =>  isset($customer->chassis_one) ? $customer->chassis_one->chassis_no : '',
                    'image' => $customer->image,
                    'customer_image'=>url('/').'/images/customer/'.$customer->image,
                ];
            })
        ];
    }
}
