<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            Log::warning('Unauthorized login attempt', ['email' => $request->email]);
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        Log::info('User logged in successfully', ['user_id' => auth()->id()]);
        return response()->json(compact('token'));
    }

    public function logout(): JsonResponse
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        Log::info('User logged out successfully', ['user_id' => auth()->id()]);
        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    public function me(): JsonResponse
    {
        return response()->json(auth()->user(), 200);
    }
}
