<?php

namespace App\Http\Resources\Customer;

use App\Http\Resources\CustomerChassisCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'code' => $customer->code,
                    'email' => $customer->email,
                    'mobile' => $customer->mobile,
                    'date' => date('Y-m-d',strtotime($customer->created_at)),
                    'product_name' => isset($customer->Products) ? $customer->Products->name : '',
                    'product_name_bn' => isset($customer->Products) ? $customer->Products->name_bn : '',
                    'model' => isset($customer->chassis_one) ? $customer->chassis_one->model: '',
                    'area_name' => isset($customer->area) ? $customer->area->name : '',
                    'district_name' => isset($customer->District) ? $customer->District->name : '',
                    'district_name_bn' => isset($customer->District) ? $customer->District->name_bn : '',
                    'UpazillaName' => isset($customer->mirror_customer->mirror_upazilla) ? $customer->mirror_customer->mirror_upazilla->UpazillaName : '',
                    'address' => $customer->address,
                    'chassis' =>  isset($customer->chassis_one) ? $customer->chassis_one->chassis_no : '',
                    'service_hour' => $customer->service_hour,
                    'image' => $customer->image,
                    'customer_image'=>url('/').'/images/customer/'.$customer->image,
                    'customer_chassis' =>  new CustomerChassisCollection($customer->customer_chassis),
                ];
            })
        ];
    }
}
