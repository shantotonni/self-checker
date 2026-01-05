<?php

namespace App\Exports;

use App\Http\Resources\ServiceRequest\ServiceRequestCollection;
use App\Models\ServiceRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CompletedServiceExport implements FromCollection, WithHeadings
{
    private $request;

    public function __construct($serviceData)
    {
        $this->request = $serviceData;
    }

    public function collection()
    {
        $request = $this->request;

        $data = $request->all();
        $search = $data['query'];
        $status = $data['status'];

        $from_date = $request->from_date;
        $to_date = date('Y-m-d',strtotime($request->to_date.' 24:59:59.000'));

        $technician_id = $request->technician_id;
        $service_type_id = $request->service_type_id;

        $service_request = ServiceRequest::query()->with('serviceType','engineer','technician','problem_Section');

        if (!empty($from_date) && !empty($to_date)){
            $service_request = $service_request->whereBetween('service_created_date',[$from_date,$to_date]);
        }
        if (!empty($status)){
            if ($status == 'completed'){
                $service_request = $service_request->where('service_request_status','completed');
            }
            if ($status == 'approved'){
                $service_request = $service_request->where('service_request_status','approved');
            }
        }
        if (!empty($technician_id)){
            $service_request = $service_request->where('technician_id',$technician_id);
        }
        if (!empty($service_type_id)){
            $service_request = $service_request->where('service_type_id',$service_type_id);
        }

        $service_request = $service_request->where(function ($query) use($search){
            if (!empty($search)){
                $query->where('generator_info','LIKE',"%$search%");
            }
        })->where(function ($query){
            $query->where('service_request_status','completed')->orWhere('service_request_status','approved');
        })->get();

        return new ServiceRequestCollection($service_request);
    }

    public function headings(): array
    {
        return [
            'id',
            'technician_id',
            'problem_section_id',
            'problem_section_name',
            'engineer_id',
            'service_type_id',
            'technician_name',
            'engineer_name',
            'service_type_name',
            'generator_info',
            'site_name',
            'location',
            'contact_person_name',
            'contact_number',
            'problem_summary',
            'information',
            'service_created_date',
            'site_arrived_date',
            'service_started_date',
            'service_completed_date',
            'service_request_no',
            'service_request_status',
            'comments',
            'initial_remarks',
            'final_remarks',
            'initial_issues',
            'final_issues'
        ];
    }
}
