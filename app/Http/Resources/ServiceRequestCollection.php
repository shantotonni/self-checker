<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceRequestCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->transform(function ($job_card) {
            return [
                'id' =>$job_card->id,
                'territory_id' =>$job_card->territory_id,
                'district_id'=>(int)$job_card->district_id,
                'upazila_id'=>(int)$job_card->upazila_id,
                'area_id' =>(int)$job_card->area_id,
                'area' =>isset($job_card->area) ? $job_card->area->name : '',
                'engineer_id' => $job_card->engineer_id,
                'engineer' =>isset($job_card->engineer) ? $job_card->engineer->name : '',
                'technitian_id' => $job_card->technitian_id,
                'technitian' =>isset($job_card->technitian) ? $job_card->technitian->name : '',
                'creator_name' =>$job_card->job_creator,
                'participant_id' =>$job_card->participant_id,
                'product' =>isset($job_card->product) ? $job_card->product->name_bn : '',
                'product_id' =>$job_card->product_id,
                'product_type'=>$job_card->product_type,
                'model_name' =>isset($job_card->model) ? $job_card->model->model_name_bn:'',
                'model_id' =>$job_card->model_id,
                'call_type' =>isset($job_card->call_type) ? $job_card->call_type->name : '',
                'call_type_id' =>$job_card->call_type_id,
                'service_type_id' =>$job_card->service_type_id,
                'service_type' =>isset($job_card->service_types) ? $job_card->service_types->name : '',
                'customer_name' =>$job_card->customer_name,
                'customer_mobile' =>$job_card->customer_mobile,
                'buy_date' => $job_card->buy_date ? date('d-m-Y',strtotime($job_card->buy_date)) : null,
                'visited_date' => $job_card->visited_date ? date('d-m-Y',strtotime($job_card->visited_date)) : null,
                'installation_date' => $job_card->installation_date ? date('d-m-Y',strtotime($job_card->installation_date)) : null,
                'service_wanted_at' => $job_card->service_wanted_at ? date('d-m-Y H:i:s',strtotime($job_card->service_wanted_at)) : null,
                'service_start_at' =>$job_card->service_start_at ? date('d-m-Y H:i:s',strtotime($job_card->service_start_at)) : null,
                'service_end_at' =>$job_card->service_end_at ? date('d-m-Y H:i:s',strtotime($job_card->service_end_at)) : null,
                'rating' => $job_card->rating,
                'job_status' => $job_card->job_status,
                'chassis_number' => $job_card->chassis_number,
                'running_houre' => $job_card->running_houre,
                'section_id' => $job_card->section_id,
                'created_at' => $job_card->created_at ? date('d-m-Y H:i:s',strtotime($job_card->created_at)) : null,
                'service_date'=> $job_card->service_date ? date('d-m-Y H:i:s',strtotime($job_card->service_date)) : null,

                'name'=>isset($job_card->product)? $job_card->product->name:'',
                'district_name'=>isset($job_card->district)? $job_card->district->name:'',
                'upazila_name'=>isset($job_card->upazila)? $job_card->upazila->name:'',
                'customer_id'=>$job_card->customer_id,
                'section_name'=>isset($job_card->section)? $job_card->section->name:'',
                'service_wanted_date'=> $job_card->service_requested_at,
            ];
        });
    }
}
