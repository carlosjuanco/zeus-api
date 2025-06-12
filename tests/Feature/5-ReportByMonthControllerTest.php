<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

use Carbon\Carbon;

class ReportByMonthControllerTest extends TestCase
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
     * Afirmar que la ruta "getYears", solo pueden ingregar los usuarios con rol 
     * "Secretaria de distrito" y "Secretaria de iglesia".
     *
     * @return void
     */
    public function test_state_that_the_getYears_route_can_only_be_added_by_users_with_the_District_secretary_and_Church_secretary_roles()
    {
        // Consultamos al usuario "Secretaria de distrito" con rol "Secretaria de distrito"
        $districSecretary = User::where('role_id', 3)->first();

        $response = $this->actingAs($districSecretary)->get('api/getYears');

        // Efectivamente el rol "Secretaria de distrito" esta autorizado a entrar
        // a esta ruta.
        $response->assertStatus(200)
            ->assertJsonStructure([
                 'years',
            ]);

        $this->post('api/logout');

        // Consultamos al usuario "Secretaria de iglesia" con rol "Secretaria de iglesia"
        $churchSecretary = User::where('role_id', 2)->first();

        $response = $this->actingAs($churchSecretary)->get('api/getYears');

        // Efectivamente el rol "Secretaria de iglesia" esta autorizado a entrar
        // a esta ruta.
        $response->assertStatus(200)
            ->assertJsonStructure([
                 'years',
            ]);

        $this->post('api/logout');

        // Consultamos al usuario "Juan Carlos" con rol "Creadores del sistema"
        $systemCreators = User::where('role_id', 1)->first();

        $response = $this->actingAs($systemCreators)->get('api/getYears');

        // Efectivamente el rol "Creadores del sismtema" no esta autorizado a entrar
        // a esta ruta.
        $response->assertStatus(403);

        $this->post('api/logout');
    }

    /**
     * Afirmar que de la funcion "obtener todos los meses que tienen informacion", por lo menos
     * hay un mes activo.
     *
     * @return void
     */
    public function test_confirm_that_from_the_get_all_months_that_have_information_function_at_least_one_month_is_active()
    {
        // Consultamos al usuario "Secretaria de iglesia" con rol "Secretaria de iglesia"
        $churchSecretary = User::where('role_id', 2)->first();

        $fecha = Carbon::now();

        $response = $this->actingAs($churchSecretary)->put('api/getAllTheMonthsThatHaveInformation/' . $fecha->year);

        $response->assertStatus(200)
            ->assertJsonStructure([
                 'months' => [
                    '*' => [
                        'id',
                        'month',
                        'anio',
                        'haveInformation',
                    ]
                ]
            ]);

        $this->post('api/logout');
    }

    /**
     * Afirmar que del método "getAllTheMonthsThatHaveInformation", solo puede entrar los usuarios
     * con rol "Secretaria de iglesia".
     *
     * @return void
     */
    public function test_state_that_only_users_with_the_church_secretary_role_can_access_the_getAllTheMonthsThatHaveInformation_method()
    {
        // Consultamos al usuario "Juan Carlos" con rol "Creadores del sistema"
        $systemCreators = User::where('role_id', 1)->first();

        $fecha = Carbon::now();

        $response = $this->actingAs($systemCreators)->put('api/getAllTheMonthsThatHaveInformation/' . $fecha->year);

        // Efectivamente el rol "Creadores del sismtema" no esta autorizado a entrar
        // a esta ruta.
        $response->assertStatus(403);

        $this->post('api/logout');

        // Consultamos al usuario "Secretaria de distrito" con rol "Secretaria de distrito"
        $districSecretary = User::where('role_id', 3)->first();

        $response = $this->actingAs($districSecretary)->put('api/getAllTheMonthsThatHaveInformation/' . $fecha->year);

        // Efectivamente el rol "Secretaria de distrito" no esta autorizado a entrar
        // a esta ruta.
        $response->assertStatus(403);

        $this->post('api/logout');

        // Consultamos al usuario "Secretaria de iglesia" con rol "Secretaria de iglesia"
        $churchSecretary = User::where('role_id', 2)->first();

        $response = $this->actingAs($churchSecretary)->put('api/getAllTheMonthsThatHaveInformation/' . $fecha->year);

        // Efectivamente el rol "Secretaria de iglesia" esta autorizado a entrar
        // a esta ruta.
        $response->assertStatus(200)
            ->assertJsonStructure([
                 'months',
            ]);

        $this->post('api/logout');
    }
}
