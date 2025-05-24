<?php

namespace App\Http\Controllers;

use App\Models\Month;
use App\Models\Human;
use App\Models\ChurcheConceptMonthHuman;
use Illuminate\Http\Request;

use Carbon\Carbon;

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
        $fecha = Carbon::now();

        $months = Month::where('anio', $fecha->year)->get();
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

    /**
     * Obtener todos los meses que tienen informacion, en base al usuario que inicio sesión. 
     * 
     * @year = Recibira el parametro año.
     * 
     * En base al año obtenemos todos los meses, despues obtenemos solo los que tengas informacion,
     * dentro de la primera coleccion, creamos la propiedad "have information", ponemos como valores
     * "true" y "falso" dependiendo del caso.
     * 
     * @return json
     */
    public function getAllTheMonthsThatHaveInformation (Request $request, $year) {
        $human = Human::where('user_id', $request->user()->id)->first();

        // dd($year);
        $months = Month::where('anio', $year)->get();

        $churcheConceptMonthHumans = ChurcheConceptMonthHuman::select('month_id')
            ->whereIn('month_id', $months->pluck('id')->toArray())
            ->where('human_id', $human->id)
            ->groupBy('month_id')
            ->get();
        // dd($churcheConceptMonthHumans);
        // dd($churcheConceptMonthHumans->where('month_id', 2)->first() ? true : false);

        $months->transform(function ($month, $key) use ($churcheConceptMonthHumans){
            $month->haveInformation = $churcheConceptMonthHumans->where('month_id', $month->id)->first() ? true : false;
            return $month;
        });

        // dd($months->toArray());

        return response()->json([
            'months' => $months->toArray()
        ], 200);
    }
}
