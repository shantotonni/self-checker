<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerAuthMiddleware
{
    function __construct()
    {
        Config::set('jwt.user', Customer::class);
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => Customer::class,
        ]]);
    }

    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        }catch (\Exception $e){
            if ($e instanceof TokenExpiredException) {
                $newToken =JWTAuth::parseToken()->refresh();
                header('Authorization: Bearer ' . $newToken);
                //return response()->json(['status'=>401,'message'=>'Token Expired'],401);
            }elseif ($e instanceof TokenInvalidException){
                return response()->json(['status'=>401,'message'=>'Token Invalid'],401);
            }else {
                return response()->json(['message'=>'Authorization Token not found','status' => 401],401);
            }
        }
        return $next($request);
    }
}
