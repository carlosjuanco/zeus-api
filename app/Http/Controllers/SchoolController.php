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
        $schools = School::select('id', 'name', 'key', 'type_of_school',
            'community_id', 'secondary_number')
            /**
             * with(): se usa para cargar relaciones (Eager Loading)
             * 
             * El problema que resuelve
             * Por defecto, cuando haces ->with('community'), Laravel trae todos los campos de 
             * la tabla communities.
             * 
             * El problema que resuelve la función anónima
             * Limita los campos que se cargan
             * 
             * ¿Por qué incluir id?
             * Laravel necesita el id (o la clave foránea que usa la relación) para poder 
             * mapear cada comunidad con su respectiva escuela.
             */
            ->with(['community' => function ($query) {
                // No olvides incluir 'id' que es la FK
                $query->select(['id', 'name']);
            }])
            ->where('name', 'like', "%$search%")
            ->orWhere('key', 'like', "%$search%")
            ->orWhere('type_of_school', 'like', "%$search%")
            ->orWhere('secondary_number', 'like', "%$search%")
            ->orWhereHas('community', function($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            })
            ->orderBy('id', 'desc')
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
            'name' => 'nullable|string|max:26',
            'key' => 'nullable|string|max:10',
            'type_of_school' => 'required|in:Primaria,Preescolar,Inicial,Albergues escolares',
            'community_id' => 'required|exists:communities,id',
            'secondary_number' => 'nullable|integer|min:0|max:9',
        ]);

        // Registramos quién guarda el registro
        $validated["human_id"] = $request->user()->id;

        // Guardar el registro
        School::create($validated);

        return response()->json([
            'message' => '¡Listo! Tus datos se guardaron bien.'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, School $school)
    {
        // Validar la solicitud
        $validated = $request->validate([
            'name' => 'nullable|string|max:26',
            'key' => 'nullable|string|max:10',
            'type_of_school' => 'required|in:Primaria,Preescolar,Inicial,Albergues escolares',
            'community_id' => 'required|exists:communities,id',
            'secondary_number' => 'nullable|integer|min:0|max:9',
        ]);

        // Actualizar el registro con binding implícito
        $school->update($validated);

        return response()->json([
            'message' => '¡Listo! Tus datos se guardaron bien.'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        // Eliminar el registro con binding implícito
        $school->delete();

        return response()->json([
            'message' => '¡Listo! Tu dato fue eliminado bien'
        ], 200);
    }

    /**
     * Show all schools
     * 
     * Este método servirá para llenar elementos select.
     * Este método ya no forma parte de los básicos para un CRUD.
     * 
     * -Solo los campos id y name.
     * -Ordenado descendentemente.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllTheSchools()
    {
        $schoolsForSelect = School::select('name', 'id')->get();

        return response()->json($schoolsForSelect, 200);
    }
}