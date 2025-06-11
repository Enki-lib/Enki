<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->senha)) {
            return response()->json([
                'status' => false,
                'message' => 'Credenciais inválidas'
            ], 401);
        }

        // Generate token
        $token = Str::random(60);
        $user->api_token = $token;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Login realizado com sucesso',
            'token' => $token,
            'user' => [
                'id' => $user->matricula,
                'name' => $user->nome,
                'email' => $user->email,
            ]
        ]);
    }
} 