<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class AuthController extends Controller
{
    public function login (Request $request) {
        $data = $request->validate([
            'email' => ['required', 'exists:users'],
            'password' => ['required']
        ]);

        $user = User::where('email', $data['email'])->first();

        if (Hash::check($data['password'], $user->password)) {
            $pages_permissions = [];

            $api_token = $user->createToken('api_token', $pages_permissions);
            $api_token = $api_token->plainTextToken;

            return response()->json(compact('api_token', 'user'), 200);
        }

        return response()->json([
            'errors' => [
                'password' => ['Contraseña incorrecta']
            ]
        ], 422);
    }

    public function check (Request $request) {
        return response()->json([
            'user' => $request->user()
        ], 200);
    }

    public function logout (Request $request) {
        $request->user()->currentAccessToken()->delete();
    }
}
