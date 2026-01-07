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

        if ($token = JWTAuth::attempt(['UserCode' => $request->Username, 'password' => $request->Password,'IsActive' => '1'])){
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
