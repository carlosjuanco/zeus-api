<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\School;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function viewAny($paginate, $search = '')
    {
        // Consultar los campos con búsqueda y paginación
        $schools = School::select('name', 'key', 'type_of_school', 'community_id', 'secondary_number')
            ->where('name', 'like', "%$search%")
            ->paginate($paginate);

        return response()->json($schools, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar la solicitud
        $validated = $request->validate([
            'name' => 'required|string|max:26',
            'key' => 'required|string|max:10',
            'type_of_school' => 'required|in:Primaria,Preescolar,Inicial,Albergues escolares',
            'community_id' => 'required|exists:communities,id',
            'secondary_number' => 'nullable|integer|min:0|max:9',
        ]);

        // Guardar el registro
        School::create($validated);

        return response()->json([
            'message' => '¡Listo! Tus datos se guardaron bien.'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validar la solicitud
        $validated = $request->validate([
            'name' => 'required|string|max:26',
            'key' => 'required|string|max:10',
            'type_of_school' => 'required|in:Primaria,Preescolar,Inicial,Albergues escolares',
            'community_id' => 'required|exists:communities,id',
            'secondary_number' => 'nullable|integer|min:0|max:9',
        ]);

        // Actualizar el registro en base al segundo parámetro ($id)
        $school = School::findOrFail($id);
        $school->update($validated);

        return response()->json([
            'message' => '¡Listo! Tus datos se guardaron bien.'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Eliminar el registro en base al parámetro recibido
        $school = School::findOrFail($id);
        $school->delete();

        return response()->json([
            'message' => '¡Listo! Tu dato fue eliminado bien'
        ], 200);
    }
}