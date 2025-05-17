<?php

namespace App\Http\Controllers;

use App\Models\Month;
use Illuminate\Http\Request;

class MonthController extends Controller
{
    /**
     * Obtener los años existentes
     * En base a la tabla "months", agrupar el campo "anio", para obtener los años.
     *
     * @return json
     */
    public function getYears (Request $request) {
        $years = Month::select('anio')->groupBy('anio')->get();
        return response()->json([
            'years' => $years->pluck('anio')->toArray()
        ], 200);
    }

    /**
     * Obtener los meses del año actual
     * En base a la tabla "months", agrupar el campo "anio", para obtener los años.
     *
     * @return json
     */
    public function getMonths (Request $request) {
        $months = Month::get();
        return response()->json([
            'months' => $months->toArray()
        ], 200);
    }

    /**
     * Del mes aperturado cerrarlo.
     * 
     * @month = El modelo del mes actual aperturado.
     *
     * @return json
     */
    public function closeMonth (Request $request, Month $month) {
        $month->status = 'Cerrado';
        $month->save();

        return response()->json([
            'message' => 'Mes cerrado correctamente'
        ], 200);
    }

    /**
     * Del mes aperturado abrirlo.
     * 
     * @month = El modelo del mes que se desea abrir.
     *
     * @return json
     */
    public function openMonth (Request $request, Month $month) {
        $month->status = 'Abierto';
        $month->save();

        return response()->json([
            'message' => 'Mes abierto correctamente'
        ], 200);
    }
}
