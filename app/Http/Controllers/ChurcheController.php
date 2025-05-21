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
    /**
     * Obtener todas las iglesias
     *
     * @return json
     */
    public function getChurches (Request $request) {
        $churches = Churche::select('name')->get();
        return response()->json([
            'churches' => $churches->pluck('name')->toArray()
        ], 200);
    }

    /**
     * Obtener todos los conceptos que le pertenecen a una iglesia en específico, esta iglesia esta vinculado
     * a un humano
     *
     * @return json
     */
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

    /**
     * Guardar el número de personas ingresadas por concepto y semana.
     * 
     * Validar el arreglo que mandamos.
     * Validamos que la propiedad "primeraSemana" sea requerido y que sea un arreglo.
     * Derivado de "primeraSemana", la propiedad, concept_id, que sea requerido, entero y que existe el registro
     *  en la tabla "concepts".
     * Derivado de "primeraSemana", la propiedad, churche_id, que sea requerido, entero y que existe el registro
     *  en la tabla "churches".
     * Derivado de "primeraSemana", la propiedad, month_id, que sea requerido, entero y que existe el registro
     *  en la tabla "months".
     * Derivado de "primeraSemana", la propiedad, week, que sea requerido, entero, tenga un valor mínimo de 1
     *  y máximo 4.
     * Derivado de "primeraSemana", la propiedad, value, que sea requerido, entero, tenga un valor mínimo de 0
     *  y máximo 1000.
     * Derivado de "primeraSemana", la propiedad, status, que sea requerido, texto, solo acepte valores
     *  "Abierto" y "Cerrado".
     * Derivado de "primeraSemana", la propiedad, id, que sea requerido y entero.
     * 
     * Validamos que la propiedad "segundaSemana" exista, si existe que sea un arreglo.
     * Validamos que la propiedad "terceraSemana" exista, si existe que sea un arreglo.
     * Validamos que la propiedad "cuartoSemana" exista, si existe que sea un arreglo.
     *
     * @return json
     */
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

        // Ocupamos el helpers "Arr::exists", para verificar que la propiedad "primeraSemana" exista.
        // Importamos la libreria.
        // Enlace: https://laravel.com/docs/9.x/helpers#main-content
        if(Arr::exists($data, 'primeraSemana')) {
            foreach ($data['primeraSemana'] as $registro) {
                $churcheConceptMonthHuman = ChurcheConceptMonthHuman::where('id', $registro['id'])
                    ->first();

                // Si existe el registro en la base de datos, actualizamos
                // de lo contrario agregamos el registro.
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
