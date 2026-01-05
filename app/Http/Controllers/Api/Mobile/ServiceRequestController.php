<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceRequest\ServiceRequestJobCardCollection;
use App\Http\Resources\ServiceRequestCollection;
use App\Models\Customer;
use App\Models\CustomerChassis;
use App\Models\District;
use App\Models\JobCard;
use App\Models\ProductModel;
use App\Models\ServiceRequest;
use App\Models\Upazila;
use App\Models\User;
use App\Models\UserArea;
use App\Models\WarrantyClaimInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Facades\JWTAuth;

class ServiceRequestController extends Controller
{
    function __construct()
    {
        Config::set('jwt.user', Customer::class);
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => Customer::class,
        ]]);
    }

    public function getAllCustomerServiceRequest(Request $request)
    {
        $chassis_no = $request->chassis_no;
        //$user = JWTAuth::parseToken()->authenticate();
        //$customer_chassis = CustomerChassis::where('customer_id',$user->id)->pluck('chassis_no');
        $job_cards = JobCard::where('chassis_number',$chassis_no)->with('model','products','district','upazila','section','technitian')
            ->orderBy('id','desc')
            ->get();
        $service_request = ServiceRequest::query()->where('chassis_number',$chassis_no)->where('is_agree', null)
            ->with('ProductModel','products','district','upazila','section')
            ->get();
        return response()->json([
           'service_request' => new ServiceRequestCollection($service_request),
           'job_card' => new ServiceRequestJobCardCollection($job_cards)
        ]);
       // return new ServiceRequestJobCardCollection($job_cards);
    }

    public function customerServiceRequest(Request $request){
     $this->validate($request,[
        'customer_mobile'=>'required',
        'section_id'=>'required',
        'model_id'=>'required',
     ]);

      $user = JWTAuth::parseToken()->authenticate();

      $product_model    = ProductModel::where('id',$request->model_id)->first();
      //$district       = District::query()->where('id',$request->district_id)->first();
      $upazila          = Upazila::query()->where('id',$request->upazila_id)->where('active','Y')->first();
      $user_area        = UserArea::query()->where('area_id', $upazila->area_id)->first();
      $engineer         = User::query()->where('id',$user_area->user_id)->first();

      $upazilaName = $upazila->name;

      $service_request = new ServiceRequest();
      $service_request->technitian_id       = '';
      $service_request->service_type_id     = null;
      $service_request->call_type_id        = 1;
      $service_request->engineer_id         = $user_area->user_id;
      $service_request->area_id             = $user_area->area_id;
      $service_request->product_id          = $product_model->id;
      $service_request->model_id            = $product_model->id;
      $service_request->section_id          = $request->section_id;
      $service_request->district_id         = $request->district_id;
      $service_request->upazila_id          = $request->upazila_id;
      $service_request->remarks             = $request->remarks;
      $service_request->customer_mobile     = $request->customer_mobile;
      $service_request->customer_name       = $user->name;
      $service_request->address             = $user->address;
      $service_request->chassis_number      = $request->chassis_number;
      $service_request->customer_id         = $user->id;
      $service_request->service_requested_at = Carbon::now();
      $service_request->request_creator     = 'customer';
      $service_request->lat                 = $request->lat;
      $service_request->lon                 = $request->lon;
      $service_request->save();

        //send otp for technician verification
        $smscontent = "$user->name".' আপনার কাছে একটি সার্ভিস চেয়েছে,উপজেলা '.$upazilaName.' চেক করুন।';

        $to = $engineer->mobile;
        $sId = '8809617615000';
        $applicationName = 'Harvester Service';
        $moduleName = 'Harvester Customer Job Card Create';
        $otherInfo = '';
        $userId = $user->id;
        $vendor = 'smsq';
        $message = $smscontent;
        $this->sendSmsQ($to, $sId, $applicationName, $moduleName, $otherInfo, $userId, $vendor, $message);

      return response()->json([
          'status'      =>'success',
           'message'    =>'Service Request Created Successfully'
          ], 200);

    }

    public function getHarvesterWarranty(Request $request){
        $chassis_no = $request->chassis_no;
        $warrantyClaims = WarrantyClaimInfo::query()->with('parts')->where('Status','!=','Inactive')
            ->where('ProductId',2)
            ->where('ChassisNumber', $chassis_no)
            ->get();
        return response()->json([
            'warranty_claims' => $warrantyClaims
        ]);
    }

    public static function sendSmsQ($to, $sId, $applicationName, $moduleName, $otherInfo, $userId, $vendor, $message)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://192.168.102.10/apps/api/send-sms/sms-master',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'To='.$to.'&SID='.$sId.'&ApplicationName='.urlencode($applicationName).'&ModuleName='.urlencode($moduleName).'&OtherInfo='.urlencode($otherInfo).'&userID='.$userId.'&Message='.$message.'&SmsVendor='.$vendor,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ));
        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

}
