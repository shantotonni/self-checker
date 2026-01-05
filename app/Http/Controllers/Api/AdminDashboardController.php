<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{

    public function checkExpired(){
        return 'ok';
    }

    public function getAdminDashboardAllData()
    {
        $data = [];
        $data['spareParts'] = 55;
        $data['twelveHour'] = 77;

        $csi = DB::table('service_request')
            ->select('service_request.id',DB::raw('SUM(question_answer.rating) as Achieve_rating'),DB::raw('COUNT(question_answer.id) * 5 as Total_rating'))
            ->join('question_answer','service_request.id','=','question_answer.service_request_id')
            ->where('service_request.service_request_status','approved')
            ->groupBy('service_request.id')
            ->get();

        //for csi
        $Achieve_rating = $csi->sum('Achieve_rating');
        $Total_rating = $csi->sum('Total_rating');
        $data['csi'] = round(($Achieve_rating/$Total_rating) * 100);

        //for service ratio
        $total_service_request = ServiceRequest::all()->count();
        $total_approved_request = ServiceRequest::where('service_request_status','approved')->count();
        $service_ratio = round(($total_approved_request /$total_service_request) *100);
        $data['service'] = $service_ratio;

        $monthYear = [];
        $monthYearDate = [];

        for ($m=1; $m<=12; $m++) {
            $monthYear[] = date('F Y', mktime(0,0,0,$m, 1, date('Y')));
            $monthYearDate[] = date('Y-m-d', mktime(0,0,0,$m, 1, date('Y')));
        }

        $userPerMonth = [];

        foreach ($monthYearDate as $month){
            $value = count(ServiceRequest::whereYear('service_approved_date', '=', date('Y',strtotime($month)))->whereMonth('service_approved_date', '=', date('m',strtotime($month)))->get());
            array_push($userPerMonth,$value);
        }

        $data['label'] = $monthYear;
        $data['value'] = $userPerMonth;

        //for technician performance
        $tecnician = DB::select('select top 5 sr.technician_id,u.name,u.Image,count(sr.technician_id) as NumberOfServices,(select CONVERT(int, round(CAST(SUM(qa.rating) as decimal(12,2)) / CAST(count(qa.service_request_id) as decimal(12,2)),0))
                                        from question_answer qa) as Rating from service_request as sr
                                        join users as u
                                            on sr.technician_id = u.id
                                        group by sr.technician_id,u.name,u.Image order by Rating desc');
        $data['technician'] = $tecnician;

        return response()->json([
            'data'=>$data
        ]);

    }
}
