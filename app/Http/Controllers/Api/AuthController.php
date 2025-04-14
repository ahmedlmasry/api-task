<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $user = User::create($request->validated());
        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => new UserResource($user)
        ]);
    }

    public function login(LoginUserRequest $request)
    {
        if (Auth::attempt($request->validated())) {
            $user = Auth::user();
            return response()->json([
                'token' => $user->createToken('auth_token')->plainTextToken
                ]);
        }
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function user(Request $request)
    {
        return response()->json(new UserResource($request->user()));
    }
}
