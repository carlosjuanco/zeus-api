<?php

namespace App\Http\Controllers;

use App\Models\Churche;
use App\Models\Human;
use App\Models\Month;
use App\Models\ChurcheConceptMonthHuman;
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

        $churcheConceptMonthHuman = ChurcheConceptMonthHuman::select('id', 'churche_id', 'concept_id', 'month_id', 
            'human_id', 'week', 'value', 'accumulated', 'status')
            ->where('month_id', $open_month->id)
            ->where('human_id', $human->user_id)
            ->orderBy('concept_id', 'asc')
            ->get();
        return response()->json([
            'churcheConceptMonthHuman' => $churcheConceptMonthHuman->toArray()
        ], 200);
    }

    public function storeChurcheWithConcepts (Request $request) {
        $data = $request->validate([
            'primeraSemana' => 'required|array',
            'primeraSemana.*.concept_id' => 'required|integer|exists:concepts,id',
            'primeraSemana.*.churche_id' => 'required|integer|exists:churches,id',
            'primeraSemana.*.month_id' => 'required|integer|exists:months,id',
            'primeraSemana.*.week' => 'required|integer|min:1|max:4',
            'primeraSemana.*.value' => 'required|integer|min:0|max:1000',
            'primeraSemana.*.status' => 'required|string|in:Abierto,Cerrado',
            'primeraSemana.*.id' => 'required|integer',

            'segundaSemana' => 'array',
            'segundaSemana.*.concept_id' => 'required|integer|exists:concepts,id',
            'segundaSemana.*.churche_id' => 'required|integer|exists:churches,id',
            'segundaSemana.*.month_id' => 'required|integer|exists:months,id',
            'segundaSemana.*.week' => 'required|integer|min:1|max:4',
            'segundaSemana.*.value' => 'required|integer|min:0|max:1000',
            'segundaSemana.*.status' => 'required|string|in:Abierto,Cerrado',
            'segundaSemana.*.id' => 'required|integer',

            'terceraSemana' => 'array',
            'terceraSemana.*.concept_id' => 'required|integer|exists:concepts,id',
            'terceraSemana.*.churche_id' => 'required|integer|exists:churches,id',
            'terceraSemana.*.month_id' => 'required|integer|exists:months,id',
            'terceraSemana.*.week' => 'required|integer|min:1|max:4',
            'terceraSemana.*.value' => 'required|integer|min:0|max:1000',
            'terceraSemana.*.status' => 'required|string|in:Abierto,Cerrado',
            'terceraSemana.*.id' => 'required|integer',

            'cuartaSemana' => 'array',
            'cuartaSemana.*.concept_id' => 'required|integer|exists:concepts,id',
            'cuartaSemana.*.churche_id' => 'required|integer|exists:churches,id',
            'cuartaSemana.*.month_id' => 'required|integer|exists:months,id',
            'cuartaSemana.*.week' => 'required|integer|min:1|max:4',
            'cuartaSemana.*.value' => 'required|integer|min:0|max:1000',
            'cuartaSemana.*.status' => 'required|string|in:Abierto,Cerrado',
            'cuartaSemana.*.id' => 'required|integer',
        ]);

        $human = Human::where('user_id', $request->user()->id)->first();

        if(Arr::exists($data, 'primeraSemana')) {
            foreach ($data['primeraSemana'] as $registro) {
                $churcheConceptMonthHuman = ChurcheConceptMonthHuman::where('id', $registro['id'])
                    ->first();
                if ($churcheConceptMonthHuman) {
                    $churcheConceptMonthHuman->update([
                        'week' => $registro['week'],
                        'value' => $registro['value'],
                        'accumulated' => 0, // o calcularlo si deseas
                        'status' => $registro['status'],
                        'month_id' => $registro['month_id'],
                        'concept_id' => $registro['concept_id'],
                        'churche_id' => $registro['churche_id'],
                        'human_id' => $human->id,
                    ]);
                } else {
                    ChurcheConceptMonthHuman::create([
                        'week' => $registro['week'],
                        'value' => $registro['value'],
                        'accumulated' => 0, // o calcularlo si deseas
                        'status' => $registro['status'],
                        'month_id' => $registro['month_id'],
                        'concept_id' => $registro['concept_id'],
                        'churche_id' => $registro['churche_id'],
                        'human_id' => $human->id,
                    ]);
                }
            }
        }

        if(Arr::exists($data, 'segundaSemana')) {
            foreach ($data['segundaSemana'] as $registro) {
                $churcheConceptMonthHuman = ChurcheConceptMonthHuman::where('id', $registro['id'])
                    ->first();
                if ($churcheConceptMonthHuman) {
                    $churcheConceptMonthHuman->update([
                        'week' => $registro['week'],
                        'value' => $registro['value'],
                        'accumulated' => 0, // o calcularlo si deseas
                        'status' => $registro['status'],
                        'month_id' => $registro['month_id'],
                        'concept_id' => $registro['concept_id'],
                        'churche_id' => $registro['churche_id'],
                        'human_id' => $human->id,
                    ]);
                } else {
                    ChurcheConceptMonthHuman::create([
                        'week' => $registro['week'],
                        'value' => $registro['value'],
                        'accumulated' => 0, // o calcularlo si deseas
                        'status' => $registro['status'],
                        'month_id' => $registro['month_id'],
                        'concept_id' => $registro['concept_id'],
                        'churche_id' => $registro['churche_id'],
                        'human_id' => $human->id,
                    ]);
                }
            }
        }

        if(Arr::exists($data, 'terceraSemana')) {
            foreach ($data['terceraSemana'] as $registro) {
                $churcheConceptMonthHuman = ChurcheConceptMonthHuman::where('id', $registro['id'])
                    ->first();
                if ($churcheConceptMonthHuman) {
                    $churcheConceptMonthHuman->update([
                        'week' => $registro['week'],
                        'value' => $registro['value'],
                        'accumulated' => 0, // o calcularlo si deseas
                        'status' => $registro['status'],
                        'month_id' => $registro['month_id'],
                        'concept_id' => $registro['concept_id'],
                        'churche_id' => $registro['churche_id'],
                        'human_id' => $human->id,
                    ]);
                } else {
                    ChurcheConceptMonthHuman::create([
                        'week' => $registro['week'],
                        'value' => $registro['value'],
                        'accumulated' => 0, // o calcularlo si deseas
                        'status' => $registro['status'],
                        'month_id' => $registro['month_id'],
                        'concept_id' => $registro['concept_id'],
                        'churche_id' => $registro['churche_id'],
                        'human_id' => $human->id,
                    ]);
                }
            }
        }

        if(Arr::exists($data, 'cuartaSemana')) {
            foreach ($data['cuartaSemana'] as $registro) {
                $churcheConceptMonthHuman = ChurcheConceptMonthHuman::where('id', $registro['id'])
                    ->first();
                if ($churcheConceptMonthHuman) {
                    $churcheConceptMonthHuman->update([
                        'week' => $registro['week'],
                        'value' => $registro['value'],
                        'accumulated' => 0, // o calcularlo si deseas
                        'status' => $registro['status'],
                        'month_id' => $registro['month_id'],
                        'concept_id' => $registro['concept_id'],
                        'churche_id' => $registro['churche_id'],
                        'human_id' => $human->id,
                    ]);
                } else {
                    ChurcheConceptMonthHuman::create([
                        'week' => $registro['week'],
                        'value' => $registro['value'],
                        'accumulated' => 0, // o calcularlo si deseas
                        'status' => $registro['status'],
                        'month_id' => $registro['month_id'],
                        'concept_id' => $registro['concept_id'],
                        'churche_id' => $registro['churche_id'],
                        'human_id' => $human->id,
                    ]);
                }
            }
        }

        return response()->json([
            'message' => '¡Listo! Tus datos se guardaron bien.'
        ], 200);
    }
}
