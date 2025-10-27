<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()
                ->json([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ], 401);
        }
        $user = Auth::user();
        return response()
            ->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()
            ->json([
                'status' => 'success',
                'message' => 'Successfully logged out'
            ]);
    }

    public function refresh()
    {
        return response()
            ->json([
                'status' => 'success',
                'user' => Auth::user(),
                'authorisation' => [
                    'token' => Auth::refresh(),
                    'type' => 'bearer',
                ]
            ]);
    }
}
