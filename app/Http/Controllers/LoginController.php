<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class LoginController extends Controller
{
    public function index(Request $request)
    {

        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json(['success' => false, 'error' => 'Unauthorised'], 401);
        } else {
            $user = Auth::user();
            $access_token = $user->createToken('authtoken')->plainTextToken;
            return response()->json(['success' => true, 'user' => $user, 'access_token' => $access_token], 200);
        }
    }
}
