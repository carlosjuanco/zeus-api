<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

use App\Models\User;
use App\Models\ChurcheConceptMonthHuman;

use Database\Seeders\CreateChurchSecretariesToTestWithSeveralChurchesSeeder;

class ChurcheControllerTest extends TestCase
{
    /**
     * Comprobar que el usuario este logueado.
     *
     * @return void
     */
    public function test_verify_that_the_user_is_logged_in()
    {
        $response = $this->post('api/getChurcheWithConcepts');
        $response->assertStatus(405);
    }

    /**
     * Comprobar que el rol "Secretaria de distrito" no puede entrar.
     *
     * @return json
     */
    public function test_ensure_that_the_district_secretary_role_cannot_log_in()
    {
        // 3 = es el rol "Secretaria de distrito" 
        $districtSecretary = User::where('role_id', 3)->first();

        $response = $this->actingAs($districtSecretary)->get('api/getChurcheWithConcepts');

        // Efectivamente el rol "Secretaria de distrito" no esta autorizado a entrar
        // a esa ruta.
        $response->assertStatus(403);

        $this->post('api/logout');
    }

    /**
     * Obtener todos los conceptos que le pertenecen a una iglesia en específico, del usuario "Secretaria de iglesia".
     *
     * @return json
     */
    public function test_get_all_items_belonging_to_a_specific_church_from_the_church_secretary_user()
    {
        // 2 = es el rol "Secretaria de iglesia" 
        $churcheSecretary = User::where('role_id', 2)->first();

        $response = $this->actingAs($churcheSecretary)->get('api/getChurcheWithConcepts');

        // Se usa el carácter * para afirmar que dentro de la estructura de todos, existe un objeto
        // y que tiene las propiedades, pero puede ser un arreglo que no tiene una longitud definida
        // https://laravel.com/docs/9.x/http-tests#assert-json-structure
        // Como lo entiendo, es decir, que tiene siempre esas propiedades.
        $response->assertStatus(200)
            ->assertJsonStructure([
                 'churcheConceptMonthHuman' => [
                    '*' => [
                        'accumulated',
                        'churche_id',
                        'concept_id',
                        'human_id',
                        'id',
                        'month_id',
                        'status',
                        'value',
                        'week',
                    ]
                 ]
             ]);

        $this->post('api/logout');
    }

    /**
     * Guardar el número de personas ingresadas por concepto y solo mandamos una semana.
     *
     * @return json
     */
    public function test_save_the_number_of_people_entered_per_item_and_we_only_send_them_for_one_week()
    {
        // 2 = es el rol "Secretaria de iglesia" 
        $churcheSecretary = User::where('role_id', 2)->first();

        // 3 = es el rol "Secretaria de distrito" 
        $districtSecretary = User::where('role_id', 3)->first();

        // Consultamos todos los meses
        $responseMonthOpen = $this->actingAs($districtSecretary)->get('api/getMonths');
        // Buscamos que mes esta bierto
        $monthOpen = Arr::where($responseMonthOpen->json()['months'], function ($value, $key) {
            return $value['status'] == 'Abierto';
        });
        // Obtenemos el primer registro, se hace de esta manera porque el indice no comienza en cero
        // sigue almacenando del primero arreglo ($responseMonthOpen->json()['months'])
        $first = Arr::first($monthOpen, function ($value, $key) {
            return $value;
        });

        $primeraSemana = [
            "primeraSemana" => [
                [
                    "concept_id" => 1,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 2,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 3,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 4,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 5,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 6,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 7,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 8,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 9,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 10,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ]
            ]
        ];

        $response = $this->actingAs($churcheSecretary)->post('api/storeChurcheWithConcepts', $primeraSemana);

        $response->assertStatus(200)
            ->assertJson(['message' => '¡Listo! Tus datos se guardaron bien.' ]);

        $this->post('api/logout');

        // Eliminar los datos guardados, para hacer bien la siguiente prueba
        $response2 = $this->actingAs($churcheSecretary)->get('api/getChurcheWithConcepts');

        $ids = Arr::pluck($response2->json()['churcheConceptMonthHuman'], 'id');
        
        $allDelete = ChurcheConceptMonthHuman::whereIn('id', $ids)->delete();

        $this->post('api/logout');
    }

    /**
     * Guardar el número de personas ingresadas por concepto y mandamos dos semana.
     *
     * @return json
     */
    public function test_save_the_number_of_people_entered_per_item_and_send_them_for_two_weeks()
    {
        // 2 = es el rol "Secretaria de iglesia" 
        $churcheSecretary = User::where('role_id', 2)->first();

        // 3 = es el rol "Secretaria de distrito" 
        $districtSecretary = User::where('role_id', 3)->first();

        // Consultamos todos los meses
        $responseMonthOpen = $this->actingAs($districtSecretary)->get('api/getMonths');
        // Buscamos que mes esta bierto
        $monthOpen = Arr::where($responseMonthOpen->json()['months'], function ($value, $key) {
            return $value['status'] == 'Abierto';
        });
        // Obtenemos el primer registro, se hace de esta manera porque el indice no comienza en cero
        // sigue almacenando del primero arreglo ($responseMonthOpen->json()['months'])
        $first = Arr::first($monthOpen, function ($value, $key) {
            return $value;
        });

        $segundaSemana = [
            "primeraSemana" => [
                [
                    "concept_id" => 1,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 2,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 3,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 4,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 5,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 6,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 7,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 8,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 9,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 10,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ]
            ],
            "segundaSemana" => [
                [
                    "concept_id" => 1,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 2,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 3,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 4,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 5,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 6,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 7,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 8,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 9,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 10,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ]
            ]
        ];

        $response = $this->actingAs($churcheSecretary)->post('api/storeChurcheWithConcepts', $segundaSemana);

        $response->assertStatus(200)
            ->assertJson(['message' => '¡Listo! Tus datos se guardaron bien.' ]);

        $this->post('api/logout');

        // Eliminar los datos guardados, para hacer bien la siguiente prueba
        $response2 = $this->actingAs($churcheSecretary)->get('api/getChurcheWithConcepts');

        $ids = Arr::pluck($response2->json()['churcheConceptMonthHuman'], 'id');
        
        $allDelete = ChurcheConceptMonthHuman::whereIn('id', $ids)->delete();

        $this->post('api/logout');
    }

    /**
     * Guardar el número de personas ingresadas por concepto y mandamos tres semana.
     *
     * @return json
     */
    public function test_save_the_number_of_people_entered_per_item_and_send_them_for_three_weeks()
    {
        // 2 = es el rol "Secretaria de iglesia" 
        $churcheSecretary = User::where('role_id', 2)->first();

        // 3 = es el rol "Secretaria de distrito" 
        $districtSecretary = User::where('role_id', 3)->first();

        // Consultamos todos los meses
        $responseMonthOpen = $this->actingAs($districtSecretary)->get('api/getMonths');
        // Buscamos que mes esta bierto
        $monthOpen = Arr::where($responseMonthOpen->json()['months'], function ($value, $key) {
            return $value['status'] == 'Abierto';
        });
        // Obtenemos el primer registro, se hace de esta manera porque el indice no comienza en cero
        // sigue almacenando del primero arreglo ($responseMonthOpen->json()['months'])
        $first = Arr::first($monthOpen, function ($value, $key) {
            return $value;
        });

        $terceraSemana = [
            "primeraSemana" => [
                [
                    "concept_id" => 1,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 2,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 3,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 4,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 5,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 6,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 7,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 8,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 9,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 10,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ]
            ],
            "segundaSemana" => [
                [
                    "concept_id" => 1,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 2,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 3,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 4,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 5,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 6,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 7,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 8,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 9,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 10,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ]
            ],
            "terceraSemana" => [
                [
                    "concept_id" => 1,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 3,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 2,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 3,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 3,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 3,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 4,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 3,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 5,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 3,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 6,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 3,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 7,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 3,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 8,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 3,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 9,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 3,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 10,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 3,
                    "value" => 0,
                    "status" => "Abierto",
                    "id" => 0
                ]
            ]
        ];

        $response = $this->actingAs($churcheSecretary)->post('api/storeChurcheWithConcepts', $terceraSemana);

        $response->assertStatus(200)
            ->assertJson(['message' => '¡Listo! Tus datos se guardaron bien.' ]);

        $this->post('api/logout');

        // Eliminar los datos guardados, para hacer bien la siguiente prueba
        $response2 = $this->actingAs($churcheSecretary)->get('api/getChurcheWithConcepts');

        $ids = Arr::pluck($response2->json()['churcheConceptMonthHuman'], 'id');
        
        $allDelete = ChurcheConceptMonthHuman::whereIn('id', $ids)->delete();

        $this->post('api/logout');
    }

    /**
     * Guardar el número de personas ingresadas por concepto y mandamos cuatro semana.
     *
     * @return json
     */
    public function test_save_the_number_of_people_entered_per_item_and_send_them_for_four_weeks()
    {
        // 2 = es el rol "Secretaria de iglesia" 
        $churcheSecretary = User::where('role_id', 2)->first();

        // 3 = es el rol "Secretaria de distrito" 
        $districtSecretary = User::where('role_id', 3)->first();

        // Consultamos todos los meses
        $responseMonthOpen = $this->actingAs($districtSecretary)->get('api/getMonths');
        // Buscamos que mes esta bierto
        $monthOpen = Arr::where($responseMonthOpen->json()['months'], function ($value, $key) {
            return $value['status'] == 'Abierto';
        });
        // Obtenemos el primer registro, se hace de esta manera porque el indice no comienza en cero
        // sigue almacenando del primero arreglo ($responseMonthOpen->json()['months'])
        $first = Arr::first($monthOpen, function ($value, $key) {
            return $value;
        });

        $cuartaSemana = [
            "primeraSemana" => [
                [
                    "concept_id" => 1,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 50,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 2,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 50,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 3,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 50,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 4,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 50,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 5,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 50,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 6,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 50,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 7,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 50,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 8,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 50,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 9,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 50,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 10,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 1,
                    "value" => 50,
                    "status" => "Abierto",
                    "id" => 0
                ]
            ],
            "segundaSemana" => [
                [
                    "concept_id" => 1,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 51,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 2,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 51,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 3,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 51,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 4,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 51,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 5,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 51,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 6,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 51,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 7,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 51,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 8,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 51,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 9,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 51,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 10,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 2,
                    "value" => 51,
                    "status" => "Abierto",
                    "id" => 0
                ]
            ],
            "terceraSemana" => [
                [
                    "concept_id" => 1,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 3,
                    "value" => 52,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 2,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 3,
                    "value" => 52,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 3,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 3,
                    "value" => 52,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 4,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 3,
                    "value" => 52,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 5,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 3,
                    "value" => 52,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 6,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 3,
                    "value" => 52,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 7,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 3,
                    "value" => 52,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 8,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 3,
                    "value" => 52,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 9,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 3,
                    "value" => 52,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 10,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 3,
                    "value" => 52,
                    "status" => "Abierto",
                    "id" => 0
                ]
            ],
            "cuartaSemana" => [
                [
                    "concept_id" => 1,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 4,
                    "value" => 53,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 2,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 4,
                    "value" => 53,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 3,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 4,
                    "value" => 53,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 4,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 4,
                    "value" => 53,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 5,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 4,
                    "value" => 53,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 6,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 4,
                    "value" => 53,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 7,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 4,
                    "value" => 53,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 8,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 4,
                    "value" => 53,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 9,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 4,
                    "value" => 53,
                    "status" => "Abierto",
                    "id" => 0
                ],
                [
                    "concept_id" => 10,
                    "churche_id" => 3,
                    "month_id" => $first['id'],
                    "week" => 4,
                    "value" => 53,
                    "status" => "Abierto",
                    "id" => 0
                ]
            ]
        ];

        $response = $this->actingAs($churcheSecretary)->post('api/storeChurcheWithConcepts', $cuartaSemana);

        $response->assertStatus(200)
            ->assertJson(['message' => '¡Listo! Tus datos se guardaron bien.' ]);

        $this->post('api/logout');
    }

    /**
     * Prueba obtener por cada iglesia la sumatoria de todas las semanas del mes aperturado
     *
     * @return json
     */
    public function test_to_get_the_sum_of_all_the_weeks_of_the_month_opened_for_each_church()
    {
        // 3 = es el rol "Secretaria de distrito" 
        $churcheSecretary = User::where('role_id', 3)->first();

        $response = $this->actingAs($churcheSecretary)->get('api/getForEachChurchTheSumOfAllTheWeeksOfTheMonthOpened');

        // Se usa el carácter * para afirmar que dentro de la estructura de todos, existe un objeto
        // y que tiene las propiedades, pero puede ser un arreglo que no tiene una longitud definida
        // https://laravel.com/docs/9.x/http-tests#assert-json-structure
        // Como lo entiendo, es decir, que tiene siempre esas propiedades.

        // No agregue la propiedad "total_week" y "total_by_concept", debido a que solo una posicion 
        // del arreglo lo cumple y de acuerdo a la descripcion del método "assertJsonStructure", 
        // todos deben ser iguales
        $response->assertStatus(200)
            ->assertJsonStructure([
                 'churches' => [
                    '*' => [
                        'id',
                        'name',
                        'concept' => [
                            '*' => [
                                'id',
                                'concept',
                            ]
                        ]
                    ]
                ]
             ]);

        $this->post('api/logout');
    }

    /**
     * Create a church secretary for the 12 existing churches
     * 
     * La finalidad es que en las pruebas unitarias visuales, se registren datos para cada iglesia
     * con los usuarios de secretarias que se registren aquí
     *
     * @return json
     */
    public function test_create_a_church_secretary_for_the_12_existing_churches()
    {
        $this->seed(CreateChurchSecretariesToTestWithSeveralChurchesSeeder::class); // Ejecuta tu seeder

        // Verifica que los datos se hayan insertado correctamente
        $this->assertDatabaseHas('users', [
            'name' => 'Secretaria de iglesia 2',
            'role_id' => 2,
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Secretaria de iglesia 3',
            'role_id' => 2,
        ]);
        $this->assertDatabaseHas('users', [
            'name' => 'Secretaria de iglesia 4',
            'role_id' => 2,
        ]);
        $this->assertDatabaseHas('users', [
            'name' => 'Secretaria de iglesia 5',
            'role_id' => 2,
        ]);
        $this->assertDatabaseHas('users', [
            'name' => 'Secretaria de iglesia 6',
            'role_id' => 2,
        ]);
        $this->assertDatabaseHas('users', [
            'name' => 'Secretaria de iglesia 7',
            'role_id' => 2,
        ]);
        $this->assertDatabaseHas('users', [
            'name' => 'Secretaria de iglesia 8',
            'role_id' => 2,
        ]);
        $this->assertDatabaseHas('users', [
            'name' => 'Secretaria de iglesia 9',
            'role_id' => 2,
        ]);
        $this->assertDatabaseHas('users', [
            'name' => 'Secretaria de iglesia 10',
            'role_id' => 2,
        ]);
        $this->assertDatabaseHas('users', [
            'name' => 'Secretaria de iglesia 11',
            'role_id' => 2,
        ]);
        $this->assertDatabaseHas('users', [
            'name' => 'Secretaria de iglesia 12',
            'role_id' => 2,
        ]);
    }
}
