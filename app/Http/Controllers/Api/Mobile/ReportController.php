<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Models\Upazila;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function getDistrictWiseDailyFoodPriceReport(Request $request)
    {
        try {
            ini_set('max_execution_time', 0);
            $conn = DB::connection('sqlsrv');

            $date = date('Y-m-d',strtotime($request->date));
            $districtID = (string)$request->districtID;
            $upazillaID = $request->upazillaID;
            $mokamID = $request->mokamID;

            $districtName = District::where('DistrictCode', $districtID)
                        ->pluck('DistrictName')
                        ->first();
            $upazillaName = Upazila::where('UpazillaCode', $upazillaID)
                        ->pluck('UpazillaName')
                        ->first();
            $mokamName = DB::table('Mokam')->where('MokamId', $mokamID)
                        ->pluck('MokamName')
                        ->first();

            try {
                DB::connection('sqlsrv')->getPdo();
            } catch (\Exception $e) {
                return response()->json(['message' => "Could not connect to the database.  Please check your configuration. error:" . $e], 400);
            }

            $sql = "EXEC districtWiseDailyFoodPriceReportForMobileApp '$date', '$date', '$districtName', '$upazillaName', '$mokamName', ''";

            $result = DB::statement($sql);

            if($result){
                $final = DB::select($sql);
                if(isset($final[0]->NoData)){
                    return response()->json(['priceReport' => [], 'msg' => 'No data found!'], 200);
                }
                return response()->json(['priceReport' => $final, 'msg' => 'Success fully generated data'], 200);
            }
            else{
                return response()->json(['priceReport' => [], 'msg' => 'No data found!'], 200);
            }
        }
        catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
