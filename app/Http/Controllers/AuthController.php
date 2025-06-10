<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Month;
use App\Models\Human;

use Carbon\Carbon;

class AuthController extends Controller
{
    public function login (Request $request) {
        $data = $request->validate([
            'email' => ['required', 'email', 'exists:users'],
            'password' => ['required']
        ]);

        $user = User::where('email', $data['email'])->first();

        if (Hash::check($data['password'], $user->password)) {
            $pages_permissions = [];
            $pages = $user->role->pages;

            foreach ($pages as $page) {
                array_push($pages_permissions, $page->name);
            }

            $apiToken = $user->createToken('api_token', $pages_permissions);
            $apiToken = $apiToken->plainTextToken;

            return response()->json(['apiToken' => $apiToken, 'user' => $user], 200);
        }

        return response()->json([
            'errors' => [
                'password' => ['Contraseña incorrecta']
            ]
        ], 422);
    }

    public function check (Request $request) {
        $user = User::where('email', $request->user()->email)->first();

        // Si no mandamos a llamar a las páginas en la línea 45, no retorna las páginas.
        // Realice varios pruebas y la única razón, por el cual en el método login, sí
        // se envian las páginas, es porque en la línea 22 se manda a llamar, pero se
        // se asigna a la variable $pages
        $user->role->pages;

        //Consultar si hay un mes aperturado
        $fecha = Carbon::now();
        $openMonth = Month::where('anio', $fecha->year)->where('status', 'Abierto')->first();
        if(!$openMonth){
            $user->monthOpen = 'No hay mes aperturado';
            $user->month_id = 0;
        } else {
            $user->monthOpen = 'Si hay mes aperturado';
            $user->month_id = $openMonth->id;
        }

        // Consultar a que iglesia pertenece el usuario
        $human = Human::where('user_id', $user->id)->first();
        $churche = $human->churche;
        
        // Solo se valida para el rol "Creadores del sistema", este no debe pertenecer a una iglesia.
        $user->churchToWhichItBelongs = $churche ? $churche->name : "";
        $user->churche_id = $churche ? $churche->id : 0;

        return response()->json([
            'user' => $user
        ], 200);
    }

    public function logout (Request $request) {
        $request->user()->currentAccessToken()->delete();
    }
}
