<?php

namespace App\Http\Controllers;
use Laravel\Sanctum\HasApiTokens;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request){
        $credentials = $request->validated();
        if (!Auth::attempt($credentials)) {
            return response([
                'message' => 'Provided email or password is incorrect'
            ], 422);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;
        return response(compact('user', 'token'));
    }
    public function signup(SignupRequest $request) {
        $data = $request->validated();
        /** @var \App\Models\User $user */
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => $data['password'],
            'role' => $data['role']
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return response(compact('user', 'token', 'errors'));
    }
    public function logout(Request $request)
    {
        $user = Auth::user();
    
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
    
        $accessToken = $request->user()->currentAccessToken();
    
        if (!$accessToken) {
            return response()->json(['message' => 'User does not have an access token'], 400);
        }
    
        $accessToken->delete();
    
        return response()->json(['message' => 'Logged out successfully'], 204);
    }
}
