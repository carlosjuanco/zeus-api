<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Models\User;
use App\Models\Human;
use App\Models\Teacher;
use App\Models\School;
use App\Models\Community;

class ReportByCommunityTest extends TestCase
{
    /**
     * Helper para obtener usuario administrativo
     */
    private function getAdministrativeUser()
    {
        return User::where('role_id', 2)->first();
    }

    /**
     * Helper para obtener usuario sin permisos
     */
    private function getUserWithoutPermission()
    {
        return User::where('role_id', '!=', 2)->first();
    }

    /**
     * Helper para obtener usuario administrativo de humanos
     */
    private function getAdministrativeHuman()
    {
        return Human::where('paternal_surname', 'administrative')->first();
    }

    // ============================================================
    // 1. PRUEBAS DE RUTAS (AUTENTICACIÓN Y PERMISOS)
    // ============================================================

    /**
     * Afirmar que no se puede acceder al método getReportByCommunity sin autenticación.
     */
    public function test_assert_that_get_report_by_community_cannot_be_accessed_without_authentication()
    {
        $response = $this->getJson('api/teachers');

        $response->assertStatus(401);
    }

    /**
     * Afirmar que no se puede acceder al método getReportByCommunity sin permiso.
     */
    public function test_assert_that_get_report_by_community_cannot_be_accessed_without_permission()
    {
        $userWithoutPermission = $this->getUserWithoutPermission();

        $response = $this->actingAs($userWithoutPermission)->getJson('api/teachers');

        $response->assertStatus(403);

        $this->post('api/logout');
    }

    /**
     * Afirmar que no se puede acceder al método getInformationFromTheTeacher sin autenticación.
     */
    public function test_assert_that_get_information_from_the_teacher_cannot_be_accessed_without_authentication()
    {
        $teacher = Teacher::first();

        $response = $this->getJson('api/teacher/' . $teacher->id);

        $response->assertStatus(401);
    }

    /**
     * Afirmar que no se puede acceder al método getInformationFromTheTeacher sin permiso.
     */
    public function test_assert_that_get_information_from_the_teacher_cannot_be_accessed_without_permission()
    {
        $userWithoutPermission = $this->getUserWithoutPermission();
        $teacher = Teacher::first();

        $response = $this->actingAs($userWithoutPermission)->getJson('api/teacher/' . $teacher->id);

        $response->assertStatus(403);

        $this->post('api/logout');
    }

    /**
     * Afirmar que no se puede acceder al método getTheStaffForACommunity sin autenticación.
     */
    public function test_assert_that_get_the_staff_for_a_community_cannot_be_accessed_without_authentication()
    {
        $community = Community::first();

        $response = $this->getJson('api/thestaffforacommunity/' . $community->id);

        $response->assertStatus(401);
    }

    /**
     * Afirmar que no se puede acceder al método getTheStaffForACommunity sin permiso.
     */
    public function test_assert_that_get_the_staff_for_a_community_cannot_be_accessed_without_permission()
    {
        $userWithoutPermission = $this->getUserWithoutPermission();
        $community = Community::first();

        $response = $this->actingAs($userWithoutPermission)->getJson('api/thestaffforacommunity/' . $community->id);

        $response->assertStatus(403);

        $this->post('api/logout');
    }

    // ============================================================
    // 2. PRUEBAS DE ESTRUCTURA DE RESPUESTA
    // ============================================================

    /**
     * Afirmar que la respuesta JSON tiene la estructura esperada del método getReportByCommunity.
     */
    public function test_assert_that_the_json_response_has_the_expected_structure_from_get_report_by_community()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers');

        $response->assertStatus(200);

        // Verificar que la respuesta es un array y que contiene los campos esperados
        // Si hay al menos un elemento, validamos su estructura
        $data = $response->json();
        if (count($data) > 0) {
            $response->assertJsonStructure([
                '*' => [
                    'idComunidad',
                    'nombreComunidad',
                    'primaria',
                    'claveEscuelaPrimaria',
                    'claveEscuelaPrimariaCompleta',
                    'directivoPrimaria',
                    'idDirectivoPrimaria',
                    'telefonoDelProfesorDeLaPrimaria',
                    'numeroProgresivoPrimaria',
                    'preescolar',
                    'claveEscuelaPreescolar',
                    'claveEscuelaPreescolarCompleta',
                    'directivoPreescolar',
                    'idDirectivoPreescolar',
                    'telefonoDelProfesorDePreescolar',
                    'numeroProgresivoPreescolar',
                    'inicial',
                    'claveEscuelaInicial',
                    'claveEscuelaInicialCompleta',
                    'directivoInicial',
                    'idDirectivoInicial',
                    'telefonoDelProfesorDeInicial',
                    'albergueEscolar',
                    'claveEscuelaAlbergueEscolar',
                    'directivoAlbergueEscolar',
                    'idDirectivoAlbergueEscolar',
                    'telefonoDelProfesorDelAlbergueEscolar'
                ]
            ]);
        } else {
            // Si está vacío, al menos debe ser un array
            $this->assertIsArray($data);
        }

        $this->post('api/logout');
    }

    /**
     * Afirmar que la respuesta JSON tiene la estructura esperada del método getInformationFromTheTeacher.
     */
    public function test_assert_that_the_json_response_has_the_expected_structure_from_get_information_from_the_teacher()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $teacher = Teacher::first();

        $response = $this->actingAs($userAdministrative)->getJson('api/teacher/' . $teacher->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'paternal_surname',
                'maternal_surname',
                'curp',
                'rfc',
                'gender',
                'budget_code',
                'funcion',
                'telephone',
                'motivo',
                'date_of_entry_into_the_sep',
                'study_profile',
                'language',
                'language_variant',
                'school_id',
                'full_name',
                'school' => [
                    'id',
                    'name'
                ]
            ]);

        $this->post('api/logout');
    }

    /**
     * Afirmar que la respuesta JSON tiene la estructura esperada del método getTheStaffForACommunity.
     */
    public function test_assert_that_the_json_response_has_the_expected_structure_from_get_the_staff_for_a_community()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $community = Community::first();

        $response = $this->actingAs($userAdministrative)->getJson('api/thestaffforacommunity/' . $community->id);

        $response->assertStatus(200);

        // Verificar que la respuesta es un array y que contiene los campos esperados
        $data = $response->json();
        if (count($data) > 0) {
            $response->assertJsonStructure([
                '*' => [
                    'id',
                    'telephone',
                    'type_of_school',
                    'full_name'
                ]
            ]);
        } else {
            // Si está vacío, al menos debe ser un array
            $this->assertIsArray($data);
        }

        $this->post('api/logout');
    }
}