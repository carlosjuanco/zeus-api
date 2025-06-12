<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

use Carbon\Carbon;

class ReportSelectedMonthTest extends TestCase
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
     * Afirmar que la ruta "getForEachChurchTheSumOfAllTheWeeksOfTheMonthSelected", solo pueden ingresar el usuario con rol 
     * "Secretaria de distrito".
     *
     * @return void
     */
    public function test_state_that_the_getForEachChurchTheSumOfAllTheWeeksOfTheMonthSelected_route_can_only_be_added_the_user_church_secretary_role()
    {
        // Consultamos al usuario "Juan Carlos" con rol "Creadores del sistema"
        $systemCreators = User::where('role_id', 1)->first();

        $fecha = Carbon::now();

        $response = $this->actingAs($systemCreators)->put('api/getForEachChurchTheSumOfAllTheWeeksOfTheMonthSelected/' . $fecha->month);

        // Efectivamente el rol "Creadores del sistema" no esta autorizado a entrar
        // a esta ruta.
        $response->assertStatus(403);

        $this->post('api/logout');

        // Consultamos al usuario "Secretaria de iglesia" con rol "Secretaria de iglesia"
        $churchSecretary = User::where('role_id', 2)->first();

        $response = $this->actingAs($churchSecretary)->put('api/getForEachChurchTheSumOfAllTheWeeksOfTheMonthSelected/' . $fecha->month);

        // Efectivamente el rol "Secretaria de iglesia" no esta autorizado a entrar
        // a esta ruta.
        $response->assertStatus(403);

        $this->post('api/logout');

        // Consultamos al usuario "Secretaria de distrito" con rol "Secretaria de distrito"
        $districtSecretary = User::where('role_id', 3)->first();

        $response = $this->actingAs($districtSecretary)->put('api/getForEachChurchTheSumOfAllTheWeeksOfTheMonthSelected/' . $fecha->month);

        // Efectivamente el rol "Secretaria de distrito" esta autorizado a entrar
        // a esta ruta.
        $response->assertStatus(200)
            ->assertJsonStructure([
                 'churches',
            ]);

        $this->post('api/logout');
    }

    /**
     * Afirmar que la ruta "getConcepts", solo pueden ingresar el usuario con rol "Secretaria de iglesia" y 
     * "Secretaria de distrito"
     *
     * @return void
     */
    public function test_assert_that_the_getConcepts_route_can_only_be_entered_by_users_with_the_Church_Secretary_and_District_Secretary_roles()
    {
        // Consultamos al usuario "Juan Carlos" con rol "Creadores del sistema"
        $systemCreators = User::where('role_id', 1)->first();

        $response = $this->actingAs($systemCreators)->get('api/getConcepts/');

        // Efectivamente el rol "Creadores del sistema" no esta autorizado a entrar
        // a esta ruta.
        $response->assertStatus(403);

        $this->post('api/logout');

        // Consultamos al usuario "Secretaria de iglesia" con rol "Secretaria de iglesia"
        $churchSecretary = User::where('role_id', 2)->first();

        $response = $this->actingAs($churchSecretary)->get('api/getConcepts/');

        // Efectivamente el rol "Secretaria de iglesia" esta autorizado a entrar
        // a esta ruta.
        $response->assertStatus(200)
            ->assertJsonStructure([
                 'concepts',
            ]);

        $this->post('api/logout');

        // Consultamos al usuario "Secretaria de distrito" con rol "Secretaria de distrito"
        $districtSecretary = User::where('role_id', 3)->first();

        $response = $this->actingAs($districtSecretary)->get('api/getConcepts/');

        // Efectivamente el rol "Secretaria de distrito" esta autorizado a entrar
        // a esta ruta.
        $response->assertStatus(200)
            ->assertJsonStructure([
                 'concepts',
            ]);

        $this->post('api/logout');
    }

    /**
     * Afirmar que la ruta "getMonths", solo pueden ingresar el usuario con rol "Secretaria de distrito".
     *
     * @return void
     */
    public function test_assert_that_the_getMonths_route_can_only_be_entered_by_the_user_with_the_District_Secretary_role()
    {
        // Consultamos al usuario "Juan Carlos" con rol "Creadores del sistema"
        $systemCreators = User::where('role_id', 1)->first();

        $response = $this->actingAs($systemCreators)->get('api/getMonths/');

        // Efectivamente el rol "Creadores del sistema" no esta autorizado a entrar
        // a esta ruta.
        $response->assertStatus(403);

        $this->post('api/logout');

        // Consultamos al usuario "Secretaria de iglesia" con rol "Secretaria de iglesia"
        $churchSecretary = User::where('role_id', 2)->first();

        $response = $this->actingAs($churchSecretary)->get('api/getMonths/');

        // Efectivamente el rol "Secretaria de iglesia" no esta autorizado a entrar
        // a esta ruta.
        $response->assertStatus(403);

        $this->post('api/logout');

        // Consultamos al usuario "Secretaria de distrito" con rol "Secretaria de distrito"
        $districtSecretary = User::where('role_id', 3)->first();

        $response = $this->actingAs($districtSecretary)->get('api/getMonths/');

        // Efectivamente el rol "Secretaria de distrito" esta autorizado a entrar
        // a esta ruta.
        $response->assertStatus(200)
            ->assertJsonStructure([
                 'months',
            ]);

        $this->post('api/logout');
    }
}
