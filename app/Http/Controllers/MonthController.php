<?php

namespace App\Http\Controllers;

use App\Models\Month;
use Illuminate\Http\Request;

use Carbon\Carbon;

class MonthController extends Controller
{
    public function getYears (Request $request) {
        $years = Month::select('anio')->groupBy('anio')->get();
        return response()->json([
            'years' => $years->pluck('anio')->toArray()
        ], 200);
    }

    public function getMonths (Request $request) {
        $fecha = Carbon::now();

        $months = Month::where('anio', $fecha->year)->get();
        return response()->json([
            'months' => $months->toArray()
        ], 200);
    }

    public function closeMonth (Request $request, Month $month) {
        $month->status = 'Cerrado';
        $month->save();

        return response()->json([
            'message' => 'Mes cerrado correctamente'
        ], 200);
    }

    public function openMonth (Request $request, Month $month) {
        $month->status = 'Abierto';
        $month->save();

        return response()->json([
            'message' => 'Mes abierto correctamente'
        ], 200);
    }
}
