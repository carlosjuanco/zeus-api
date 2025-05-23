<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

use App\Models\User;

use Carbon\Carbon;

class MonthControllerTest extends TestCase
{
    /**
     * Comprobar que el usuario este logueado.
     *
     * @return void
     */
    public function test_verify_that_the_user_is_logged_in()
    {
        $response = $this->post('api/getYears');
        $response->assertStatus(405);
    }

    /**
     * Comprobar que solo el rol "secretaria de distrito" puede entrar.
     *
     * @return json
     */
    public function test_that_only_the_district_secretary_role_can_enter()
    {
        $system_creators = User::where('role_id', 1)->first();

        $response = $this->actingAs($system_creators)->get('api/getYears');

        // Efectivamente el rol "Secretaria de distrito" no esta autorizado a entrar
        // a esa ruta.
        $response->assertStatus(403);

        $this->post('api/logout');
    }

    /**
     * Comprobar que existan años
     *
     * @return json
     */
    public function test_years_exists()
    {
        $district_secretary = User::where('role_id', 3)->first();

        $response = $this->actingAs($district_secretary)->get('api/getYears');

        $response->assertStatus(200)
            ->assertJsonFragment(['years' => [2025]]);

        $this->post('api/logout');
    }

    /**
     * Obtener todos los meses.
     *
     * @return json
     */
    public function test_get_every_month()
    {
        $district_secretary = User::where('role_id', 3)->first();

        $response = $this->actingAs($district_secretary)->get('api/getMonths');

        $response->assertStatus(200)
            ->assertJsonStructure([
                 'months',
             ])
             ->assertJsonCount(12, 'months');

        $this->post('api/logout');
    }

    /**
     * Close one month
     *
     * @return json
     */
    public function test_close_one_month()
    {
        $district_secretary = User::where('role_id', 3)->first();
        // Consultamos todos los meses
        $responseMonthOpen = $this->actingAs($district_secretary)->get('api/getMonths');
        // Buscamos que mes esta bierto
        $monthOpen = Arr::where($responseMonthOpen->json()['months'], function ($value, $key) {
            return $value['status'] == 'Abierto';
        });
        // Obtenemos el primer registro, se hace de esta manera porque el indice no comienza en cero
        // sigue almacenando del primero arreglo ($responseMonthOpen->json()['months'])
        $first = Arr::first($monthOpen, function ($value, $key) {
            return $value;
        });

        // Mandamos a cerrar el mes actualmente abierto
        $response = $this->actingAs($district_secretary)->put('api/closeMonth/' . $first['id']);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Mes cerrado correctamente']);

        $this->post('api/logout');
    }

    /**
     * Open one month
     *
     * @return json
     */
    public function test_open_one_month()
    {
        $fecha = Carbon::now();

        $district_secretary = User::where('role_id', 3)->first();

        $response = $this->actingAs($district_secretary)->put('api/openMonth/' . $fecha->month);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Mes abierto correctamente']);

        $this->post('api/logout');
    }
}
