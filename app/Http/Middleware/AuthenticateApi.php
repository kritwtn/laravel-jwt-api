<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
class AuthenticateApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,$roles = 'all')
    {
        try {
            if (!$request->bearerToken()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
            $token =  $request->bearerToken();
            if(empty($token)){
                throw new \Exception('Bearer token not found');
            }
            
            $user = User::where('remember_token', $token)->first();
            if(!$user) {
                throw new \Exception('Not Found account');
            }
          
            if($roles == 'all'){
                return $next($request);
            } else {
                $roles = explode('|',$roles);
                if(is_array($roles) && count($roles) > 0) {
                    foreach ($roles as  $role) {
                        if($user->role === $role) {
                            return $next($request);
                        }
                    }
                }
            }
            return response()->json(['error' => 'Not have permission'], 403);
            
            
        } catch (\Exception $e) {
            return response()->json(['error' =>  $e->getMessage()], 500);
        }
        
        return $next($request);
    }
}
