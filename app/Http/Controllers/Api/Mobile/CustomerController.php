<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\CustomerInfoResource;
use App\Http\Resources\CustomerProfileResource;
use App\Http\Resources\ServiceRequest\ServiceRequestCollection;
use App\Http\Resources\WarrantyPartsCollection;
use App\Models\Customer;
use App\Models\CustomerChassis;
use App\Models\JobCard;
use App\Models\PartsDetail;
use App\Models\ServiceRequest;
use App\Models\SmartAssist;
use App\Models\User;
use App\Models\WarrantyClaimInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerController extends Controller
{
    function __construct()
    {
        Config::set('jwt.user', Customer::class);
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => Customer::class,
        ]]);
    }

    public function warrantyParts(Request $request){
        $chassis = $request->ChassisNumber;
        //$customer_warranty_parts = WarrantyClaimInfo::where('ChassisNumber',$request->ChassisNumber)->where('Status','Approved')->get();
        $parts = PartsDetail::with('warranty_claim')->whereHas('warranty_claim', function($query) use ($chassis){
            $query->where('ChassisNumber', $chassis);
            $query->where('Status', 'Approved');
        })->get();
        return new WarrantyPartsCollection($parts);
    }

    public function harvesterSmartAssist(Request $request){
        $smart_assist = SmartAssist::where('chassis_no',$request->chassis_no)
            //->where('password',$request->password)
            ->first();
        if ($smart_assist){
            return response()->json([
                'status'=>'success',
                'smart_assist'=>$smart_assist
            ]);
        }else{
            return response()->json([
                'status'=>'error',
                'message'=>'Not Match'
            ]);
        }
    }

    public function getCustomerProfile(Request $request){
        $chassis_no = $request->chassis_no;
        $customer_chassis = CustomerChassis::query()->with(['customer','mirror_customer','mirror_customer.mirror_district','mirror_customer.mirror_upazilla'])
            ->where('chassis_no', $chassis_no)->first();

        return response()->json([
            'status'            => 'success',
            'user'  => new CustomerProfileResource($customer_chassis)
        ]);
    }

    public function customerFeedback(Request $request){
        $job_card_id        = $request->job_card_id;
        $customer_rating    = $request->customer_rating;
        $customer_remarks   = $request->customer_remarks;

        $job_card = JobCard::query()->where('id',$job_card_id)->first();
        $job_card->customer_rating  = $customer_rating;
        $job_card->customer_remarks = $customer_remarks;
        $job_card->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'আপনার ফিডব্যাক টি সফলভাবে সম্পন্ন হয়েছে। '
        ]);
    }
}
