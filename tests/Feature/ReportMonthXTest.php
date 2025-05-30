<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

use Carbon\Carbon;

class ReportMonthXTest extends TestCase
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
     * Afirmar que la ruta "getChurcheWithConceptsWithMonth", solo pueden ingresar el usuario con rol 
     * "Secretaria de iglesia".
     *
     * @return void
     */
    // public function test_state_that_the_getChurcheWithConceptsWithMonth_route_can_only_be_added_the_user_church_secretary_role()
    // {
    //     // Consultamos al usuario "Juan Carlos" con rol "Creadores del sistema"
    //     $systemCreators = User::where('role_id', 1)->first();

    //     $fecha = Carbon::now();

    //     $response = $this->actingAs($systemCreators)->put('api/getChurcheWithConceptsWithMonth/' . $fecha->month);

    //     // Efectivamente el rol "Creadores del sistema" esta autorizado a entrar
    //     // a esta ruta.
    //     $response->assertStatus(200)
    //         ->assertJsonStructure([
    //              'churcheConceptMonthHuman',
    //         ]);

    //     $this->post('api/logout');

    //     // Consultamos al usuario "Secretaria de iglesia" con rol "Secretaria de iglesia"
    //     $churchSecretary = User::where('role_id', 2)->first();

    //     $response = $this->actingAs($churchSecretary)->put('api/getChurcheWithConceptsWithMonth/' . $fecha->month);

    //     // Efectivamente el rol "Secretaria de iglesia" esta autorizado a entrar
    //     // a esta ruta.
    //     $response->assertStatus(200)
    //         ->assertJsonStructure([
    //              'churcheConceptMonthHuman',
    //         ]);

    //     $this->post('api/logout');

    //     // Consultamos al usuario "Secretaria de distrito" con rol "Secretaria de distrito"
    //     $districtSecretary = User::where('role_id', 3)->first();

    //     $response = $this->actingAs($districtSecretary)->put('api/getChurcheWithConceptsWithMonth/' . $fecha->month);

    //     // Efectivamente el rol "Secretaria de distrito" no esta autorizado a entrar
    //     // a esta ruta.
    //     $response->assertStatus(403);

    //     $this->post('api/logout');
    // }

    /**
     * Afirmar que la ruta "monthlyReportOfTheChurchSecretary", solo pueden ingresar el usuario con rol 
     * "Secretaria de iglesia".
     *
     * @return void
     */
    // public function test_state_that_the_monthlyReportOfTheChurchSecretary_route_can_only_be_added_by_user_church_secretary_role()
    // {
    //     // Consultamos al usuario "Juan Carlos" con rol "Creadores del sistema"
    //     $systemCreators = User::where('role_id', 1)->first();

    //     $fecha = Carbon::now();

    //     $response = $this->actingAs($systemCreators)->put('api/monthlyReportOfTheChurchSecretary/' . $fecha->month);

    //     // Efectivamente el rol "Creadores del sistema" esta autorizado a entrar
    //     // a esta ruta.
    //     $response->assertStatus(200)
    //         ->assertJsonStructure([
    //              'file',
    //         ]);

    //     $this->post('api/logout');

    //     // Consultamos al usuario "Secretaria de iglesia" con rol "Secretaria de iglesia"
    //     $churchSecretary = User::where('role_id', 2)->first();

    //     $response = $this->actingAs($churchSecretary)->put('api/monthlyReportOfTheChurchSecretary/' . $fecha->month);

    //     // Efectivamente el rol "Secretaria de iglesia" esta autorizado a entrar
    //     // a esta ruta.
    //     $response->assertStatus(200)
    //         ->assertJsonStructure([
    //              'file',
    //         ]);

    //     $this->post('api/logout');

    //     // Consultamos al usuario "Secretaria de distrito" con rol "Secretaria de distrito"
    //     $districtSecretary = User::where('role_id', 3)->first();

    //     $response = $this->actingAs($districtSecretary)->put('api/monthlyReportOfTheChurchSecretary/' . $fecha->month);

    //     // Efectivamente el rol "Secretaria de distrito" no esta autorizado a entrar
    //     // a esta ruta.
    //     $response->assertStatus(403);

    //     $this->post('api/logout');
    // }
}
