<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerProfileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->customer->id,
            'product_id' => $this->customer->product_id,
            'product_name' => isset($this->customer->product) ? $this->customer->product->name : '',
            'product_name_bn' => isset($this->customer->product) ? $this->customer->product->name_bn : '',
            'name' => $this->customer->name,
            'email' => $this->customer->email,
            'mobile' => $this->customer->mobile,
            'service_hour' => $this->customer->service_hour,
            'date_of_purchase' => $this->customer->date_of_purchase,
            'area_name' => isset($this->customer->area) ? $this->customer->area->name : '',
            'area_name_bn' => isset($this->customer->area) ? $this->customer->area->name_bn : '',
            'district_name' => isset($this->customer->District) ? $this->customer->District->name : '',
            'district_name_bn' => isset($this->customer->District) ? $this->customer->District->name_bn : '',
            'address' => $this->customer->address,
            'customer_type' => $this->customer->customer_type,
            'image' => $this->customer->image,
            'customer_image'=>url('/').'/images/customer/'.$this->customer->image,
            'customer_chassis' => [
                                    'id'                => $this->id,
                                    'customer_id'       => $this->customer_id,
                                    'customer_code'     => $this->customer_code,
                                    'model_id'          => $this->model_id,
                                    'model_name'        => $this->model,
                                    'chassis_no'        => $this->chassis_no,
                                    'service_hour'      => $this->service_hour,
                                    'date_of_purchase'  => $this->date_of_purchase,
                                    'last_service_date' => $this->last_service_date,
                                    'CustomerName1'     => $this->mirror_customer ? $this->mirror_customer->CustomerName1 : '',
                                    'CustomerName2'     => $this->mirror_customer ? $this->mirror_customer->CustomerName2 : '',
                                    'Address1'          => $this->mirror_customer ? $this->mirror_customer->Address1 : '',
                                    'Address2'          => $this->mirror_customer ? $this->mirror_customer->Address2 : '',
                                    'Mobile'            => $this->mirror_customer ? $this->mirror_customer->Mobile : '',
                                    'DistrictCode'      => $this->mirror_customer->mirror_district ? $this->mirror_customer->mirror_district->DistrictCode : '',
                                    'DistrictName'      => $this->mirror_customer->mirror_district ? $this->mirror_customer->mirror_district->DistrictName : '',
                                    'UpazillaCode'      => $this->mirror_customer->mirror_upazilla ? $this->mirror_customer->mirror_upazilla->UpazillaCode : '',
                                    'UpazillaName'      => $this->mirror_customer->mirror_upazilla ? $this->mirror_customer->mirror_upazilla->UpazillaName : '',
                                ]
        ];
    }
}
