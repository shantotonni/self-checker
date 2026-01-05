<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwtauth:api', ['except' => ['login','dashboardLogin', 'mobileSignUp', 'sendOtp', 'resetPasswordWithOtp']]);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'Username' => 'required',
            'Password' => 'required',
        ]);

        if ($token = JWTAuth::attempt(['CustomerMobile' => $request->Username, 'password' => $request->Password,'Status' => 'Y'])){
             $user = Auth::user();
            return response()->json([
                'status'=>200,
                'token'=>$token,
                'user' => $user
            ],200);
        }
        return response()->json([
            'message'=>'Username or Password Not Match',
            'status'=>401
        ],200);
    }

    public function mobileSignUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customerName'   => 'required|string|max:255',
            'customerMobile' => 'required|string|max:20|unique:PriceSurveyCustomer,CustomerMobile',
            'address'         => 'required|string|max:255',
            'password'        => 'required|string|min:6|max:255',
            'lat'             => 'nullable|string|max:255',
            'long'            => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $customerId = DB::table('PriceSurveyCustomer')->insertGetId([
                'CustomerName'   => $request->customerName,
                'CustomerMobile' => $request->customerMobile,
                'Address'        => $request->address,
                'Password'       => Hash::make($request->password),
                'Lat'            => $request->lat,
                'Long'           => $request->long,
                'Status'         => 'Y',
                'CreatedDate'    => Carbon::now(),
                'UpdatedDate'    => Carbon::now(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Registration successful.'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Registration failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customerMobile' => 'required|string|exists:PriceSurveyCustomer,CustomerMobile',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $otpCode = rand(100000, 999999);

            DB::table('PriceSurveyOTP')->insert([
                'CustomerMobile' => $request->customerMobile,
                'OTPCode' => $otpCode,
                'ExpiresAt' => Carbon::now()->addMinutes(5), // OTP valid for 5 minutes
                'CreatedAt' => Carbon::now(),
                'UpdatedAt' => Carbon::now(),
            ]);

            //send SMS here using any SMS gateway
            $smscontent                 = 'Otp Code - ' . $otpCode;

            $to                 = $request->customerMobile;
            $sId                = '8809617615000';
            $applicationName    = 'PriceSurvey';
            $moduleName         = 'ForgotPassword';
            $otherInfo          = 'Motors';
            $userId             = '01521495184';
            $vendor             = 'smsq';
            $message            = $smscontent;
            $this->sendSmsQ($to, $sId, $applicationName, $moduleName, $otherInfo, $userId, $vendor, $message);

            return response()->json([
                'status' => true,
                'message' => 'OTP sent successfully.',
                'otp_code' => $otpCode
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to send OTP.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function resetPasswordWithOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customerMobile' => 'required|string|exists:PriceSurveyCustomer,CustomerMobile',
            'otpCode'        => 'required|string|size:6',
            'new_password'    => 'required|string|min:6|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $otp = DB::table('PriceSurveyOTP')
                ->where('CustomerMobile', $request->customerMobile)
                ->where('OTPCode', $request->otpCode)
                ->where('IsUsed', false)
                ->where('ExpiresAt', '>', Carbon::now())
                ->first();

            if (!$otp) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid or expired OTP.',
                ], 400);
            }

            $affected = DB::table('PriceSurveyCustomer')
                ->where('CustomerMobile', $request->customerMobile)
                ->update([
                    'Password' => Hash::make($request->new_password),
                    'UpdatedDate' => Carbon::now(),
                ]);

            if ($affected) {
                DB::table('PriceSurveyOTP')->where('id', $otp->id)->update(['IsUsed' => true]);

                return response()->json([
                    'status' => true,
                    'message' => 'Password reset successfully.',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Password reset failed.',
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
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

    public function me()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json($user);
    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        try {

            $user = Auth::user();
            $update_user = User::where('id',$user->id)->first();
            $update_user->device_token = '';
            $update_user->save();

            $this->guard()->logout();

        } catch (\Exception $exception) {

        }
        return response()->json([
            'status'=>200,
            'message' => 'Successfully logged out'
        ],200);

    }

}
