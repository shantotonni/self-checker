<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeliveryInfo;
use App\Models\GeneratorInfo;
use App\Models\ServiceRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getDashboardAllDara(){
        $data = [];
//        $data['total_generator'] = GeneratorInfo::count();
//        $data['total_pending_delivery'] = DeliveryInfo::where('delivery_status','!=','delivered')->count();
//        $data['total_completed_delivery'] = DeliveryInfo::where('delivery_status','delivered')->count();
//        $data['total_pending_service_request'] = ServiceRequest::where('service_request_status','!=','approved')->count();
//        $data['total_completed_service_request'] = ServiceRequest::where('service_request_status','approved')->count();
//
//        $month = [];
//        $userPerMonth = [];
//        for ($m=1; $m<=12; $m++) {
//            $age= 12 - $m;
//            $lable = date('F', mktime(0,0,0,$m, 1, date('Y')));
//            $value = count(ServiceRequest::whereMonth('service_created_date', '=', date('n') -$age)->get());
//            array_push($month,$lable);
//            array_push($userPerMonth,$value);
//        }
//
//        $data['label'] = $month;
//        $data['value'] = $userPerMonth;
//        return response()->json([
//            'data'=>$data
//        ]);
    }

    public function checkExpired(){
        return 'ok';
    }

}
