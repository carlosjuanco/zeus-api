<?php

namespace App\Http\Controllers;

use App\Models\Month;
use Illuminate\Http\Request;

use Carbon\Carbon;

class MonthController extends Controller
{
    public function getMonthOpen (Request $request) {
        $fecha = Carbon::now();
        $open_month = Month::where('anio', $fecha->year)->where('status', 'Abierto')->first();
        if(!$open_month){
            return response()->json([
                'month' => 'No hay mes aperturado'
            ], 200);
        } else {
            return response()->json([
                'month' => $open_month->toArray()
            ], 200);
        }
    }

    public function getYears (Request $request) {
        $years = Month::select('anio')->groupBy('anio')->get();
        return response()->json([
            'years' => $years->pluck('anio')->toArray()
        ], 200);
    }

    public function getMonths (Request $request) {
        $months = Month::get();
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
