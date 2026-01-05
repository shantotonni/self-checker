<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerRequest;
use App\Http\Resources\Customer\CustomerCollection;
use App\Http\Resources\Customer\CustomerInfoResource;
use App\Models\Customer;
use App\Models\CustomerChassis;
use App\Models\JobCard;
use App\Models\ProductModel;
use App\Models\StockBatch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerController extends Controller
{
    function __construct() {
        Config::set('jwt.user', Customer::class);
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => Customer::class,
        ]]);
    }

    public function index(Request $request){
        $customers = Customer::with(['ProductModel', 'Products', 'area', 'District', 'chassis_one','mirror_customer','mirror_customer.mirror_upazilla','customer_chassis','customer_chassis.mirror_customer'])
            ->orderBy('id', 'desc')
            ->where('customer_type', 'harvester')
            ->orderBy('created_at','desc');
            if ($request->isExport == 'Y'){
                $customers = $customers->get();
            }else{
                $customers = $customers->paginate(10);
            }

        return new CustomerCollection($customers);
    }

    public function store(CustomerRequest $request){

        DB::beginTransaction();
        try {
            if ($request->image) {
                $image = $request->image;
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->save(public_path('images/customer/') . $name);
            } else {
                $name = 'not_found.jpg';
            }

            $sdmsCustomerInfo = DB::connection('MotorBrInvoiceMirror')->table('InvoiceDetailsBatch')
                ->select('InvoiceDetailsBatch.Invoiceno','InvoiceDetailsBatch.BatchNo as ChassisNo','Invoice.CustomerCode','Customer.Address1','Invoice.InvoiceDate')
                ->join('Invoice','Invoice.InvoiceNo','=','InvoiceDetailsBatch.Invoiceno')
                ->join('Customer','Customer.CustomerCode','=','Invoice.CustomerCode')
                ->where('BatchNo',$request->chassis)->first();

            $CustomerCode = '';
            $Address = '';
            $DateOfPurchase = null;

            if ($sdmsCustomerInfo){
                $CustomerCode   = $sdmsCustomerInfo->CustomerCode;
                $Address        = $sdmsCustomerInfo->Address1;
                $DateOfPurchase = $sdmsCustomerInfo->InvoiceDate;
            }else{
                $CustomerCode       = $request->code;
                //$Address          = $request->address;
            }

            $exist_customer = Customer::where('mobile', $request->mobile)->where('customer_type', 'harvester')->exists();
            if ($exist_customer) {
                return response()->json([
                    'status' => "error",
                    'message' => 'Mobile number Already Exists'
                ], 200);
            }

            $getLastServiceHour = JobCard::query()->where('chassis_number',$request->chassis)
                ->where('is_approved',1)
                ->orderBy('created_at','desc')->first();

            $customer                       = new Customer();
            $customer->code                 = $CustomerCode;
            $customer->name                 = $request->name;
            $customer->mobile               = $request->mobile;
            $customer->email                = $request->email;
            $customer->image                = $name;
            $customer->address              = $Address;
            $customer->date_of_purchase     = $DateOfPurchase;
            $customer->service_hour         = $getLastServiceHour ? $getLastServiceHour->hour : 0;
            $customer->district_id          = $request->district_id;
            $customer->last_service_date    = $getLastServiceHour ? $getLastServiceHour->service_date : null;
            $customer->area_id              = $request->area_id;
            $customer->product_id           = $request->product_id;
            $customer->password             = bcrypt($request->password);
            $customer->customer_type        = 'harvester';
            $customer->save();
            if ($customer->save()) {
                $existingChassisCheck = CustomerChassis::query()->where('chassis_no',$request->chassis)->exists();
                if ($existingChassisCheck){
                    return response()->json([
                        'status' => "error",
                        'message' => 'Chassis Already Exist!',
                    ], 200);
                }

                $product_model = ProductModel::query()->where('id',$request->model_id)->first();

                $customer_chassis                   = new CustomerChassis();
                $customer_chassis->customer_id      = $customer->id;
                $customer_chassis->customer_code    = $CustomerCode;
                $customer_chassis->model_id         = $product_model->id;
                $customer_chassis->model            = $product_model->model_name_bn;
                $customer_chassis->chassis_no       = $request->chassis;
                $customer_chassis->date_of_purchase     = $DateOfPurchase;
                $customer_chassis->service_hour         = $getLastServiceHour ? $getLastServiceHour->hour : 0;
                $customer_chassis->last_service_date    = $getLastServiceHour ? $getLastServiceHour->service_date : null;
                $customer_chassis->save();

                DB::commit();
                return response()->json([
                    'status'    => "success",
                    'message'   => 'Customer Created Successfully',
                ], 200);
            } else {
                return response()->json([
                    'status'    => "error",
                    'message'   => 'Something went wrong!',
                ], 200);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'    => "error",
                'message'   => $e->getMessage()
            ], 200);
        }
    }

    public function update(CustomerRequest $request, $id)
    {
        DB::beginTransaction();

        $customer   = Customer::where('id', $id)->first();
        $image      = $request->image;

        if ($image != $customer->image) {
            if ($request->has('image')) {
                $destinationPath = 'images/customer/';
                if ($customer->image){
                    $file_old = public_path('/').$destinationPath.$customer->image;
                    if (file_exists($file_old)){
                        unlink($file_old);
                    }
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->save(public_path('images/customer/') . $name);
            } else {
                $name = $customer->image;
            }
        }
        else{
            $name = $customer->image;
        }

        $customer->name                 = $request->name;
        $customer->mobile               = $request->mobile;
        $customer->email                = $request->email ? $request->email : '';
        $customer->address              = $request->address;
        $customer->service_hour         = $request->service_hour;
        $customer->district_id          = $request->district_id;
        //$customer->date_of_purchase   = $request->date_of_purchase;
        $customer->area_id              = $request->area_id;
        $customer->product_id           = $request->product_id;
        $customer->password             = bcrypt($request->password);
        $customer->customer_type        = 'harvester';
        $customer->image                = $name ;
        if ($customer->save()) {
            $customer_chassis               = CustomerChassis::where('customer_id', $request->id)->first();
            $customer_chassis->model        = $request->model;
            $customer_chassis->chassis_no   = $request->chassis;
            //$customer_chassis->save();

            DB::commit();
            return response()->json([
                'message' => 'Customer Updated Successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => "error",
                'message' => 'Something went wrong!',
            ], 200);
        }
    }

    public function search($query){
        $customers = Customer::where('name', 'LIKE', "%$query%")
//            ->orWhere('CustomerCode', 'like', '%' . $query . '%')
//            ->orderBy('CustomerID','desc')
            ->paginate(10);
        return new CustomerCollection($customers);
    }

    public function addChassis(Request $request){
        try {
            $customer   = JWTAuth::parseToken()->authenticate();

            $exiats = CustomerChassis::where('chassis_no',$request->chassis)->exists();
            if ($exiats){
                return response()->json([
                    'message'   => 'Chassis Already Registered',
                    'status'    => 'success'
                ], 200);
            }

            $sdmsCustomerInfo = DB::connection('MotorBrInvoiceMirror')->table('InvoiceDetailsBatch')
                ->select('InvoiceDetailsBatch.Invoiceno','InvoiceDetailsBatch.BatchNo as ChassisNo','Invoice.CustomerCode','Customer.Address1','Invoice.InvoiceDate')
                ->join('Invoice','Invoice.InvoiceNo','=','InvoiceDetailsBatch.Invoiceno')
                ->join('Customer','Customer.CustomerCode','=','Invoice.CustomerCode')
                ->where('BatchNo',$request->chassis)->first();

            $chassis            = StockBatch::where('BatchNo', $request->chassis)->with('product')->first();
            $productModel       = ProductModel::query()->where('product_id',4)->get();

            $CustomerCode = '';
            $Address = '';
            $DateOfPurchase = null;

            if ($sdmsCustomerInfo){
                $CustomerCode   = $sdmsCustomerInfo->CustomerCode;
                $Address        = $sdmsCustomerInfo->Address1;
                $DateOfPurchase = $sdmsCustomerInfo->InvoiceDate;
            }else{
                $CustomerCode       = $request->code;
                //$Address          = $request->address;
            }

            if ($chassis) {
                $productName = '';
                if ($chassis){
                    if ($chassis->ProductCode == 'W051'){
                        $productName    = $productModel[4]->model_name_bn;
                        $modelId        = $productModel[4]->id;
                    }elseif ($chassis->ProductCode == 'W098'){
                        $productName    = $productModel[5]->model_name_bn;
                        $modelId        = $productModel[5]->id;
                    }elseif ($chassis->ProductCode == 'W112'){
                        $productName    = $productModel[0]->model_name_bn;
                        $modelId        = $productModel[0]->id;
                    }elseif ($chassis->ProductCode == 'W127'){
                        $productName    = $productModel[0]->model_name_bn;
                        $modelId        = $productModel[0]->id;
                    }elseif ($chassis->ProductCode == 'W133'){
                        $productName    = $productModel[4]->model_name_bn;
                        $modelId        = $productModel[4]->id;
                    }elseif ($chassis->ProductCode == 'W146'){
                        $productName    = $productModel[3]->model_name_bn;
                        $modelId        = $productModel[3]->id;
                    }elseif ($chassis->ProductCode == 'W148'){
                        $productName    = $productModel[1]->model_name_bn;
                        $modelId        = $productModel[1]->id;
                    }elseif ($chassis->ProductCode == 'W150'){
                        $productName    = $productModel[0]->model_name_bn;
                        $modelId        = $productModel[0]->id;
                    }elseif ($chassis->ProductCode == 'W151'){
                        $productName    = $productModel[0]->model_name_bn;
                        $modelId        = $productModel[0]->id;
                    }else{
                        $productName    = $productModel[5]->model_name_bn;
                        $modelId        = $productModel[5]->id;
                    }
                }

                $getLastServiceHour = JobCard::query()->where('chassis_number',$request->chassis)
                    ->where('is_approved',1)
                    ->orderBy('created_at','desc')->first();

                $customer_chassis                   = new CustomerChassis();
                $customer_chassis->customer_id      = $customer->id;
                $customer_chassis->customer_code    = $CustomerCode ? $CustomerCode : '';
                $customer_chassis->model_id         = $modelId;
                $customer_chassis->model            = $productName;
                $customer_chassis->chassis_no       = $chassis->BatchNo;
                $customer_chassis->date_of_purchase     = $DateOfPurchase;
                $customer_chassis->service_hour         = $getLastServiceHour ? $getLastServiceHour->hour : 0;
                $customer_chassis->last_service_date    = $getLastServiceHour ? $getLastServiceHour->service_date : null;
                $customer_chassis->save();

                $customer = Customer::where('id',$customer->id)->where('customer_type','harvester')->with('customer_chassis','customer_chassis.mirror_customer')->first();

                return response()->json([
                    'status'  => 'success',
                    'user'    => new CustomerInfoResource($customer),
                ]);
            } else {
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'No Found Data',
                ]);
            }
        }catch (\Exception $exception){
            return response()->json([
                'status'    => 'error',
                'message'   => $exception->getMessage(),
            ]);
        }
    }

    public function customerStatus(Request $request){
        $current_date = Carbon::now()->format('Y-m-d');
        $cuustomer_code = $request->CustomerCode;
        $business = $request->Business;
        if (empty($cuustomer_code)){
            return response()->json([
                'status'=>1,
                'message'=>'Customer Code Not Found',
            ]);
        }
        $sql = "exec sp_CustomerStatus '%', 'Jan 1 2005', '$current_date', 'RPT', 'N','0', 'W', '$cuustomer_code'";
        $conn = DB::connection('MotorBrInvoiceMirror');
        $pdo = $conn->getPdo()->prepare($sql);
        $pdo->execute();
        $res = array();
        do {
            $rows = $pdo->fetchAll(\PDO::FETCH_ASSOC);
            $res[] = $rows;
        } while ($pdo->nextRowset());

        if (!empty($res)){
            $result = [];
            foreach ($res[0] as $item){
                if ($item['DueInstNo'] == $item['CollectInstNo']){
                    $DueInstNo = 0;
                }else{
                    $DueInstNo = $item['NoOfInstallment'] - $item['OverDueInstNo'];
                }
                $result = [
                    'TotalSellingPrice' => $item['PrincipalAmount'] + $item['DownPayment'] + $item['SubsidyApproved'],
                    'ReceivedAmount' => $item['DownPayment'] + $item['SubsidyReceived'] + $item['CollectAmount'],
                    'DownPayment' => $item['DownPayment'],
                    'OverDueTaka' => $item['OverDueTaka'],
                    'SubsidyApproved' => $item['SubsidyApproved'],
                    'DueInstNo' => number_format($DueInstNo,2),
                    'TotalOutstanding' => round($item['TotalOutstanding']),
                    'LastPaymentDate' => date('Y-m-d',strtotime($item['LastPaymentDate'])),
                ];
            }
        }
        return response()->json([
            'status'=>1,
            'data'=> $result,
        ]);
    }
}
