<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store (Request $request) {
        $data = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'unique:users'],
            'role_id' => ['required', 'exists:roles,id']
        ]);

        $data['password'] = Hash::make('password');

        User::create($data);

        return response()->json([
            'message' => 'Usuario creado correctamente'
        ], 200);
    }
}
