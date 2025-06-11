<?php

namespace App\Http\Controllers;

use App\Models\Churche;
use App\Models\Human;
use App\Models\Month;
use App\Models\Concept;
use App\Models\ChurcheConceptMonthHuman;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use PDF;

class ChurcheController extends Controller
{
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
        $openMonth = Month::where('anio', $fecha->year)->where('status', 'Abierto')->first();
        if(!$openMonth){
            $openMonth = (object) ['id' => 0];
        }

        $churcheConceptMonthHuman = ChurcheConceptMonthHuman::select('id', 'churche_id', 'concept_id', 'month_id', 
            'human_id', 'week', 'value', 'accumulated', 'status')
            ->where('month_id', $openMonth->id)
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

    /**
     * Obtener por cada iglesia la sumatoria de todas las semanas del mes aperturado
     *
     * @return json
     */
    public function getForEachChurchTheSumOfAllTheWeeksOfTheMonthOpened (Request $request) {
        $churches = Churche::select('id', 'name')->orderBy('id', 'asc')->get();
        $churchesIds = $churches->pluck('id')->toArray();

        $concepts = Concept::select('id', 'concept')->orderBy('id', 'asc')->get();

        //Consultar el mes aperturado
        $fecha = Carbon::now();
        $openMonth = Month::where('anio', $fecha->year)->where('status', 'Abierto')->first();
        if(!$openMonth){
            $openMonth = (object) ['id' => 0];
        }

        $churcheConceptMonthHuman = ChurcheConceptMonthHuman::select('id', 'churche_id', 'concept_id', 'month_id', 
            'human_id', 'week', 'value', 'accumulated', 'status')
            ->where('month_id', $openMonth->id)
            ->whereIn('churche_id', $churchesIds)
            ->orderBy('id', 'asc')
            ->orderBy('concept_id', 'asc')
            ->orderBy('churche_id', 'asc')            
            ->get();

        /*
            Ordenar el campo "concept_id" de manera ascendente, también ordenamos el campo "churche_id" 
            de forma ascendente, sumar el campo "value", lo podemos agrupar por este campo, para obtener
            la suma correcta. Cuando tengamos la consulta, recorremos cada iglesia, para obtener la suma
            total entre la intersección de concepto e iglesia (total por semana). Enseguida le agregamos
            una propiedad "Total distrital" y aquí vamos guardando ésta sumatoria.
        */
        $churches->transform(function ($churche, $key) use ($churcheConceptMonthHuman, $concepts){
            $churche->concept = $concepts->map(function ($concepto) use ($churche, $churcheConceptMonthHuman) {
                $cloned = clone $concepto;
                $cloned->totalWeek = $churcheConceptMonthHuman
                    ->where('churche_id', $churche->id)
                    ->where('concept_id', $cloned->id)
                    ->sum('value');
                return $cloned;
            });
            return $churche;
        });
        /*
            Al arreglo de modelos "$churches", agregamos un nuevo modelo, en ese el atributo
            name sera igual a "Total distrital". Ahora en su propiedad "concept", tendrá el
            atributo "tota_by_concept", en ese sumanos de forma vertical
        */
        $district_total = new Churche();
        $district_total->id = 0;
        $district_total->name = 'Total distrital';
        
        $churches->push($district_total);

        $churches->last(function ($churche, $key) use ($churcheConceptMonthHuman, $concepts){
            $churche->concept = $concepts->map(function ($concepto) use ($churche, $churcheConceptMonthHuman) {
                $cloned = clone $concepto;
                $cloned->totalByConcept = $churcheConceptMonthHuman
                    ->where('concept_id', $cloned->id)
                    ->sum('value');
                return $cloned;
            });
            return $churche;
        });

        /*
            Del mismo método anterior, realizamos la misma consulta, solo que esta vez, consultamos
            especificamente del mes anterior. A la variable donde almacena la consulta anterior,
            le agregamos una fila más, con el nombre de la propiedad "Anterior", de ésta consulta,
            recorremos y sumamos la intersección entre el concepto e iglesia y vamos sumando el total,
            para ir almacenandolo, al final.
        */

        //Consultar el mes anterior aperturado
        
        $churcheConceptMonthHumanPrevious = ChurcheConceptMonthHuman::select('id', 'churche_id', 'concept_id', 'month_id', 
            'human_id', 'week', 'value', 'accumulated', 'status')
            ->where('month_id', $openMonth->month_id)
            ->whereIn('churche_id', $churchesIds)
            ->orderBy('id', 'asc')
            ->orderBy('concept_id', 'asc')
            ->orderBy('churche_id', 'asc')            
            ->get();

        $previous_total = new Churche();
        $previous_total->id = 0;
        $previous_total->name = 'Anterior';
        
        $churches->push($previous_total);

        $churches->last(function ($churche, $key) use ($churcheConceptMonthHumanPrevious, $concepts){
            $churche->concept = $concepts->map(function ($concepto) use ($churche, $churcheConceptMonthHumanPrevious) {
                $cloned = clone $concepto;
                $cloned->totalByConcept = $churcheConceptMonthHumanPrevious
                    ->where('concept_id', $cloned->id)
                    ->sum('value');
                return $cloned;
            });
            return $churche;
        });

        return response()->json([
            'churches' => $churches->toArray()
        ], 200);
    }

    /**
     * Obtener todos los conceptos que le pertenecen a una iglesia en específico, de un mes en especifico
     *
     * @return json
     */
    public function getChurcheWithConceptsWithMonth (Request $request, $month_id) {
        $human = Human::where('user_id', $request->user()->id)->first();

        $churcheConceptMonthHuman = ChurcheConceptMonthHuman::select('id', 'churche_id', 'concept_id', 'month_id', 
            'human_id', 'week', 'value', 'accumulated', 'status')
            ->where('month_id', $month_id)
            ->where('human_id', $human->user_id)
            ->orderBy('concept_id', 'asc')
            ->get();
        return response()->json([
            'churcheConceptMonthHuman' => $churcheConceptMonthHuman->toArray()
        ], 200);
    }

    /**
     * Reporte mensual de la secretaria de iglesia
     *
     * @return json
     */
    public function monthlyReportOfTheChurchSecretary(Request $request, $month_id)
    {
        // Consultar todos los conceptos
        $concepts = Concept::select('id', 'concept')->orderBy('id', 'asc')->get();

        // Obtener todos los conceptos que le pertenecen a una iglesia en específico, de un mes en especifico
        // Del método anterior.
        
        $human = Human::where('user_id', $request->user()->id)->first();

        $churcheConceptMonthHuman = ChurcheConceptMonthHuman::select('id', 'churche_id', 'concept_id', 'month_id', 
            'human_id', 'week', 'value', 'accumulated', 'status')
            ->where('month_id', $month_id)
            ->where('human_id', $human->user_id)
            ->orderBy('concept_id', 'asc')
            ->get();

        $semanas = collect([]);
        $itIsRepeated1 = false;
        $itIsRepeated2 = false;
        $itIsRepeated3 = false;
        $itIsRepeated4 = false;
        $churcheConceptMonthHuman->each(function ($churche) use (&$semanas, &$itIsRepeated1, &$itIsRepeated2, &$itIsRepeated3, &$itIsRepeated4) {
            if($churche->week == 1) {
                if($itIsRepeated1 == false) {
                    $semanas[0] = collect([]);
                }

                $semanas[0]->push((object)[
                    'id' => $churche->id,
                    'value' => $churche->value,
                ]);

                $itIsRepeated1 = true;
            }

            if($churche->week == 2) {
                if($itIsRepeated2 == false) {
                    $semanas[1] = collect([]);
                }

                $semanas[1]->push((object)[
                    'id' => $churche->id,
                    'value' => $churche->value,
                ]);

                $itIsRepeated2 = true;
            }

            if($churche->week == 3) {
                if($itIsRepeated3 == false) {
                    $semanas[2] = collect([]);
                }

                $semanas[2]->push((object)[
                    'id' => $churche->id,
                    'value' => $churche->value,
                ]);

                $itIsRepeated3 = true;
            }

            if($churche->week == 4) {
                if($itIsRepeated4 == false) {
                    $semanas[3] = collect([]);
                }

                $semanas[3]->push((object)[
                    'id' => $churche->id,
                    'value' => $churche->value,
                ]);

                $itIsRepeated4 = true;
            }
        });

        $datos = [
            'titulo' => 'Iglesia ' . $human->churche->name,
            'contenido' => [
                'concepts' => $concepts,
                'semanas' => $semanas
            ]
        ];

        $file = 'monthly-report-of-the-church-secretary.pdf';

        $pdf = PDF::loadView('monthly-report-of-the-church-secretary', $datos)->setPaper('letter', 'landscape')->output();

        Storage::put('public/' . $file, $pdf);

        return response()->json([
            'file' => $file
        ], 200);
    }

    /**
     * Obtener por cada iglesia la sumatoria de todas las semanas del mes seleccionado
     *
     * @return json
     */
    public function getForEachChurchTheSumOfAllTheWeeksOfTheMonthSelected (Request $request, $monthId) {
        $churches = Churche::select('id', 'name')->orderBy('id', 'asc')->get();
        $churchesIds = $churches->pluck('id')->toArray();

        $concepts = Concept::select('id', 'concept')->orderBy('id', 'asc')->get();

        //Consultar el mes aperturado
        $openMonth = Month::where('id', $monthId)->first();

        $churcheConceptMonthHuman = ChurcheConceptMonthHuman::select('id', 'churche_id', 'concept_id', 'month_id', 
            'human_id', 'week', 'value', 'accumulated', 'status')
            ->where('month_id', $openMonth->id)
            ->whereIn('churche_id', $churchesIds)
            ->orderBy('id', 'asc')
            ->orderBy('concept_id', 'asc')
            ->orderBy('churche_id', 'asc')            
            ->get();

        /*
            Ordenar el campo "concept_id" de manera ascendente, también ordenamos el campo "churche_id" 
            de forma ascendente, sumar el campo "value", lo podemos agrupar por este campo, para obtener
            la suma correcta. Cuando tengamos la consulta, recorremos cada iglesia, para obtener la suma
            total entre la intersección de concepto e iglesia (total por semana). Enseguida le agregamos
            una propiedad "Total distrital" y aquí vamos guardando ésta sumatoria.
        */
        $churches->transform(function ($churche, $key) use ($churcheConceptMonthHuman, $concepts){
            $churche->concept = $concepts->map(function ($concepto) use ($churche, $churcheConceptMonthHuman) {
                $cloned = clone $concepto;
                $cloned->totalWeek = $churcheConceptMonthHuman
                    ->where('churche_id', $churche->id)
                    ->where('concept_id', $cloned->id)
                    ->sum('value');
                return $cloned;
            });
            return $churche;
        });
        /*
            Al arreglo de modelos "$churches", agregamos un nuevo modelo, en ese el atributo
            name sera igual a "Total distrital". Ahora en su propiedad "concept", tendrá el
            atributo "tota_by_concept", en ese sumanos de forma vertical
        */
        $district_total = new Churche();
        $district_total->id = 0;
        $district_total->name = 'Total distrital';
        
        $churches->push($district_total);

        $churches->last(function ($churche, $key) use ($churcheConceptMonthHuman, $concepts){
            $churche->concept = $concepts->map(function ($concepto) use ($churche, $churcheConceptMonthHuman) {
                $cloned = clone $concepto;
                $cloned->totalByConcept = $churcheConceptMonthHuman
                    ->where('concept_id', $cloned->id)
                    ->sum('value');
                return $cloned;
            });
            return $churche;
        });

        /*
            Del mismo método anterior, realizamos la misma consulta, solo que esta vez, consultamos
            especificamente del mes anterior. A la variable donde almacena la consulta anterior,
            le agregamos una fila más, con el nombre de la propiedad "Anterior", de ésta consulta,
            recorremos y sumamos la intersección entre el concepto e iglesia y vamos sumando el total,
            para ir almacenandolo, al final.
        */

        //Consultar el mes anterior aperturado
        
        $churcheConceptMonthHumanPrevious = ChurcheConceptMonthHuman::select('id', 'churche_id', 'concept_id', 'month_id', 
            'human_id', 'week', 'value', 'accumulated', 'status')
            ->where('month_id', $openMonth->month_id)
            ->whereIn('churche_id', $churchesIds)
            ->orderBy('id', 'asc')
            ->orderBy('concept_id', 'asc')
            ->orderBy('churche_id', 'asc')            
            ->get();

        $previous_total = new Churche();
        $previous_total->id = 0;
        $previous_total->name = 'Anterior';
        
        $churches->push($previous_total);

        $churches->last(function ($churche, $key) use ($churcheConceptMonthHumanPrevious, $concepts){
            $churche->concept = $concepts->map(function ($concepto) use ($churche, $churcheConceptMonthHumanPrevious) {
                $cloned = clone $concepto;
                $cloned->totalByConcept = $churcheConceptMonthHumanPrevious
                    ->where('concept_id', $cloned->id)
                    ->sum('value');
                return $cloned;
            });
            return $churche;
        });

        return response()->json([
            'churches' => $churches->toArray()
        ], 200);
    }
}
