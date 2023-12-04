<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $customMessages = [
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least :min characters.',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:4',
        ], $customMessages);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        // Check if the email already exists
        if (User::where('email', $request->input('email'))->exists()) {
            return response()->json(['error' => 'Email already exists'], 422);
        }
        
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        return response()->json(['user' => $user]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        auth()->user()->update(['remember_token' => $token]);
        return $this->respondWithToken($token);
    }

    public function logout(Request $request)
    {
        try {
            $token =  $request->bearerToken();
            if(empty($token)){
                throw new \Exception('Bearer token not found');
            }
            
            $user = User::where('remember_token', $token)->first();
            if(!$user) {
                throw new \Exception('Not Found account');
            }
      
            $user->update([
                'remember_token' => null,
            ]);
            return response()->json(['error' => 'Logout success'], 200);
            
        } catch (\Exception $e) {
            return response()->json(['error' =>  $e->getMessage()], 500);
        }
    }

    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh());
    }

    protected function respondWithToken($token)
    {   
      
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
        ]);
    }
}
