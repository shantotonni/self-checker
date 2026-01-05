<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerChassisCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->transform(function ($customer_chassis) {
                return [
                    'id'                => $customer_chassis->id,
                    'customer_id'       => $customer_chassis->customer_id,
                    'customer_code'     => $customer_chassis->customer_code,
                    'model_id'          => $customer_chassis->model_id,
                    'model_name'        => $customer_chassis->model,
                    'chassis_no'        => $customer_chassis->chassis_no,
                    'service_hour'      => $customer_chassis->service_hour,
                    'date_of_purchase'  => $customer_chassis->date_of_purchase,
                    'last_service_date' => $customer_chassis->last_service_date,
                    'CustomerName1'     => $customer_chassis->mirror_customer ? $customer_chassis->mirror_customer->CustomerName1 : '',
                    'CustomerName2'     => $customer_chassis->mirror_customer ? $customer_chassis->mirror_customer->CustomerName2 : '',
                    'Address1'          => $customer_chassis->mirror_customer ? $customer_chassis->mirror_customer->Address1 : '',
                    'Address2'          => $customer_chassis->mirror_customer ? $customer_chassis->mirror_customer->Address2 : '',
                    'Mobile'            => $customer_chassis->mirror_customer ? $customer_chassis->mirror_customer->Mobile : '',
                ];
            });

    }
}
