<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Community;

class CommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Consultar el campo name de la tabla community, primeros 10 registros
        $communities = Community::select('name')
            ->take(10)
            ->get();

        return response()->json($communities, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar la solicitud
        $validated = $request->validate([
            'name' => 'required|string|max:25',
        ]);

        // Guardar el registro
        Community::create($validated);

        return response()->json([
            'message' => '¡Listo! Tus datos se guardaron bien.'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Community $community)
    {
        // Validar la solicitud
        $validated = $request->validate([
            'name' => 'required|string|max:25',
        ]);

        // Actualizar el registro usando binding implícito
        $community->update($validated);

        return response()->json([
            'message' => '¡Listo! Tus datos se guardaron bien.'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Community $community)
    {
        // Eliminar el registro usando binding implícito
        $community->delete();

        return response()->json([
            'message' => '¡Listo! Tu dato fue eliminado bien'
        ], 200);
    }
}