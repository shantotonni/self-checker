<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_name' => isset($this->product) ? $this->product->name : '',
            'product_name_bn' => isset($this->product) ? $this->product->name_bn : '',
            'model' => isset($this->Customer_chassis) ? $this->Customer_chassis->model: '',
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'password' => $this->password,
            'service_hour' => $this->service_hour,
            'date_of_purchase' => $this->date_of_purchase,
            'area_id' => $this->area_id,
            'area_name' => isset($this->area) ? $this->area->name : '',
            'area_name_bn' => isset($this->area) ? $this->area->name_bn : '',
            'district_id' => $this->district_id,
            'district_name' => isset($this->District) ? $this->District->name : '',
            'district_name_bn' => isset($this->District) ? $this->District->name_bn : '',
            'address' => $this->address,
            'chassis' =>  isset($this->Customer_chassis) ? $this->Customer_chassis->chassis_no : '',
            'chassis_image' => $this->chassis_image,
            'customer_type' => $this->customer_type,
            'image' => $this->image,
            'customer_image'=>url('/').'/images/customer/'.$this->image,
        ];
    }
}
