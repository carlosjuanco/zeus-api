<?php

namespace App\Http\Controllers;

use App\Models\Churche;
use App\Models\Human;
use App\Models\Month;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use Carbon\Carbon;

class ChurcheController extends Controller
{
    public function getChurches (Request $request) {
        $churches = Churche::select('name')->get();
        return response()->json([
            'churches' => $churches->pluck('name')->toArray()
        ], 200);
    }

    public function getChurcheWithConcepts (Request $request) {
        $human = Human::where('user_id', $request->user()->id)->first();

        //Consultar si hay un mes aperturado
        $fecha = Carbon::now();
        $open_month = Month::where('anio', $fecha->year)->where('status', 'Abierto')->first();
        if(!$open_month){
            $open_month = (object) ['id' => 0];
        }

        $churches = Churche::where('churche_id', $human->churche_id)
            ->where('month_id', $open_month->id)
            ->where('human_id', $human->user_id)
            ->orderBy('concept_id', 'asc')
            ->get();
        return response()->json([
            'churches' => $churches->toArray()
        ], 200);
    }

    public function storeChurcheWithConcepts (Request $request) {
        $data = $request->validate([
            'primeraSemana' => 'required|array',
            'primeraSemana.*.concept_id' => 'required|integer|exists:concepts,id',
            'primeraSemana.*.churche_id' => 'required|integer|exists:churches,id',
            'primeraSemana.*.month_id' => 'required|integer|exists:months,id',
            'primeraSemana.*.week' => 'required|integer|min:1|max:4',
            'primeraSemana.*.valor' => 'required|integer|min:0|max:1000',
            'primeraSemana.*.status' => 'required|string|in:Abierto,Cerrado',

            'segundaSemana' => 'array',
            'segundaSemana.*.concept_id' => 'required|integer|exists:concepts,id',
            'segundaSemana.*.churche_id' => 'required|integer|exists:churches,id',
            'segundaSemana.*.month_id' => 'required|integer|exists:months,id',
            'segundaSemana.*.week' => 'required|integer|min:1|max:4',
            'segundaSemana.*.valor' => 'required|integer|min:0|max:1000',
            'segundaSemana.*.status' => 'required|string|in:Abierto,Cerrado',

            'terceraSemana' => 'array',
            'terceraSemana.*.concept_id' => 'required|integer|exists:concepts,id',
            'terceraSemana.*.churche_id' => 'required|integer|exists:churches,id',
            'terceraSemana.*.month_id' => 'required|integer|exists:months,id',
            'terceraSemana.*.week' => 'required|integer|min:1|max:4',
            'terceraSemana.*.valor' => 'required|integer|min:0|max:1000',
            'terceraSemana.*.status' => 'required|string|in:Abierto,Cerrado',

            'cuartaSemana' => 'array',
            'cuartaSemana.*.concept_id' => 'required|integer|exists:concepts,id',
            'cuartaSemana.*.churche_id' => 'required|integer|exists:churches,id',
            'cuartaSemana.*.month_id' => 'required|integer|exists:months,id',
            'cuartaSemana.*.week' => 'required|integer|min:1|max:4',
            'cuartaSemana.*.valor' => 'required|integer|min:0|max:1000',
            'cuartaSemana.*.status' => 'required|string|in:Abierto,Cerrado',
        ]);

        $human = Human::where('user_id', $request->user()->id)->first();

        if(Arr::exists($data, 'primeraSemana')) {
            // return $data['primeraSemana'][0]['churche_id'];
            $churche = Churche::where('id', $data['primeraSemana'][0]['churche_id'])->first();
            // return $churche;
            foreach ($data['primeraSemana'] as $registro) {
                $churche->concepts()->attach($registro['concept_id'], [
                    'week' => $registro['week'],
                    'value' => $registro['valor'],
                    'accumulated' => 0, // o calcularlo si deseas
                    'status' => $registro['status'],
                    'month_id' => $registro['month_id'],
                    'human_id' => $human->id,
                ]);
            }
        }

        // return response()->json([
        //     'message' => 'Datos validados correctamente.',
        //     'data' => $data
        // ], 200);

        // return response()->json([
        //     'message' => ' creada correctamente'
        // ], 200);
    }
}
