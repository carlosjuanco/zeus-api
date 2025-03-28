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
        return response()->json([
            'month' => $open_month->toArray()
        ], 200);
    }

    public function getYears (Request $request) {
        $years = Month::select('anio')->groupBy('anio')->get();
        return response()->json([
            'years' => $years->pluck('anio')->toArray()
        ], 200);
    }
}
