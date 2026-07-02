<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTAuthController extends Controller
{
    public function Login(Request $request)
    {
        $credentials = $request->only('email','password');
        $token = JWTAuth::attempt($credentials);
        
        try{
            if(!$token){
                return response()->json(['error' => 'Invalid Credentials'],400);
            }
        }catch(JWTException  $e){
            return response()->json(['error'=>'could_not_create_token'],500);
        }

        return response()->json(compact('token'));
    }
}
