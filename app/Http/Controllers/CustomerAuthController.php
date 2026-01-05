<?php

namespace App\Http\Controllers;

use App\Http\Resources\Customer\CustomerInfoCollection;
use App\Http\Resources\Customer\CustomerInfoResource;
use App\Models\AdminUser;
use App\Models\Customer;
use App\Models\CustomerChassis;
use App\Models\JobCard;
use App\Models\Otp;
use App\Models\ProductModel;
use App\Models\StockBatch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerAuthController extends Controller
{
    function __construct() {
        Config::set('jwt.user', AdminUser::class);
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => AdminUser::class,
        ]]);
    }

    public function dashboardLogin(Request $request)
    {

        $this->validate($request, [
            'Username' => 'required',
            'Password' => 'required',
        ]);
        $username = $request->Username;
        $password = $request->Password;
        $user = DB::select("SELECT dbo.ufn_PasswordDecode(Password) as DecodPassword  FROM UserManager where UserId='$username'");

        if ($user) {
            if ($user[0]->DecodPassword == $password) {
                $user = AdminUser::where('UserId', $username)->first();
                Auth::login($user);

                $token = JWTAuth::fromUser($user,['UserId' => $user->UserId]);
                return response()->json([
                    'status'=>200,
                    'token'=>$token,
                    'user' => Auth::user()
                ],200);
                //return $this->respondWithToken($token);
            }
        }

        return response()->json([
            'message'=>'Username or Password Not Match',
            'status'=>401
        ],200);

//        if (!$token = JWTAuth::attempt(['username' => $request->Username, 'password' => $request->Password,'is_active' =>1,'role_id'=>[1]])){
//            return response()->json([
//                'message'=>'Username or Password Not Match',
//                'status'=>401
//            ],200);
//        }

//        return response()->json([
//            'status'=>200,
//            'token'=>$token,
//            'user' => Auth::user()
//        ],200);
    }



    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'previous_password' => 'required',
            'password' => 'required',
        ]);

        $current_password = Auth::User()->password;

        $customer = JWTAuth::parseToken()->authenticate();

        if (Hash::check($request->previous_password, $current_password)) {
            if (Hash::check($request->password, $current_password)) {
                return response()->json(['message' => 'Previous Password and Old Password Same']);
            } else {
                $customer = Customer::where('id', $customer->id)->first();
                $customer->password = bcrypt($request->password);

                $customer->save();
                return response()->json([
                    'status'=>'success',
                    'message' => 'Password Change successfully :)'
                ]);
            }

        } else {
            return response()->json([
                'status'=>'error',
                'message' => 'Previous Password Not Correct :)'
            ]);
        }
    }

    public function me(){
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json($user);
    }

    public function logout(): \Illuminate\Http\JsonResponse{
        try {
            $this->guard()->logout();
        } catch (\Exception $exception) {

        }
        return response()->json([
            'status' => 200,
            'message' => 'Successfully logged out'
        ], 200);
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
