<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->header('Authorization')==null){
            return response()->json(['error'=>'Token not provided'],401);
        }

        try{
            JWTAuth::parseToken()->authenticate();
        }catch(JWTException $e){
            return response()->json(['error'=>'Token is invalid'],401);
        }
        return $next($request);
    }
}
