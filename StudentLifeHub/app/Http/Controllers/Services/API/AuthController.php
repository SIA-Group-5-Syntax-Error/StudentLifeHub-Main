<?php

namespace App\Http\Controllers\Services\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // 🌟 1. This must be imported
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller // 🌟 2. Make sure it says AuthController (with the "h")
{
    // 🌟 3. Crucial: Make sure (Request $request) is inside these parentheses!
    public function login(Request $request) 
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('StudentLifeHubToken')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Authenticated successfully.',
                'data' => [
                    'token' => $token
                ]
            ], 200);
        }

        return response()->json([
            'error' => [
                'code' => 'unauthorized',
                'message' => 'Invalid email or password credentials provided.'
            ]
        ], 401);
    }
}