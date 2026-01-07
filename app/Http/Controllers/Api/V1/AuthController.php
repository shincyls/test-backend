<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $member = Member::where('username', $request->username)->first();

        if (!$member || !Hash::check($request->password, $member->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$member->isActive) {
            throw ValidationException::withMessages([
                'username' => ['This account has been deactivated.'],
            ]);
        }

        $token = $member->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'member' => $member,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:members',
            'email' => 'required|string|email|max:255|unique:members',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
        ]);

        $member = Member::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
            'role' => 'member',
            'isActive' => true,
            'current_points' => 0,
        ]);

        $token = $member->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'token' => $token,
            'member' => $member,
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}

