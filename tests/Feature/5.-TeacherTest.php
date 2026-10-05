<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Models\User;
use App\Models\Human;
use App\Models\School;
use App\Models\Teacher;

class TeacherTest extends TestCase
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

    /**
     * Helper para obtener una escuela existente para pruebas
     */
    private function getTestSchool()
    {
        return School::first();
    }

    /**
     * Helper para obtener datos válidos de un profesor
     */
    private function getValidTeacherData($schoolId = null)
    {
        if ($schoolId === null) {
            $schoolId = $this->getTestSchool()->id;
        }

        return [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'maternal_surname' => 'ApellidoMaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'funcion' => 'Docente',
            'telephone' => '951 123 4567',
            'motivo' => 1,
            'date_of_entry_into_the_sep' => '15/03/2020',
            'study_profile' => 'Titulado de U.P.N.',
            'language' => 'Mixteca',
            'language_variant' => 'Alta',
            'school_id' => $schoolId
        ];
    }

    // ============================================================
    // 1. PRUEBAS DE CREACIÓN
    // ============================================================

    /**
     * Afirmar que se puede crear un profesor.
     */
    public function test_assert_that_a_teacher_can_be_created()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $administrativeHuman = $this->getAdministrativeHuman();
        $school = $this->getTestSchool();

        $teacherData = $this->getValidTeacherData($school->id);
        $teacherData['name'] = 'ProfesorCreado';
        $teacherData['curp'] = 'PROF800101HDFRRR99';
        $teacherData['rfc'] = 'PROF800101HDF';

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', $teacherData);

        $response->assertStatus(200)
            ->assertJson(['message' => '¡Listo! Tus datos se guardaron bien.']);

        // Verificar que el nuevo profesor fue creado en la base de datos
        $this->assertDatabaseHas('teachers', [
            'name' => 'ProfesorCreado',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR99',
            'school_id' => $school->id,
            'human_id' => $administrativeHuman->id
        ]);

        $this->post('api/logout');
    }

    /**
     * Afirmar que no se puede crear un profesor sin autenticación.
     */
    public function test_assert_that_a_teacher_cannot_be_created_without_authentication()
    {
        $school = $this->getTestSchool();
        $teacherData = $this->getValidTeacherData($school->id);

        $response = $this->postJson('api/teachers/store', $teacherData);

        $response->assertStatus(401);
    }

    /**
     * Afirmar que no se puede crear un profesor sin permiso.
     */
    public function test_assert_that_a_teacher_cannot_be_created_without_permission()
    {
        $userWithoutPermission = $this->getUserWithoutPermission();
        $school = $this->getTestSchool();
        $teacherData = $this->getValidTeacherData($school->id);

        $response = $this->actingAs($userWithoutPermission)->postJson('api/teachers/store', $teacherData);

        $response->assertStatus(403);
        $this->post('api/logout');
    }

    /**
     * Afirmar que se puede crear un profesor con campos opcionales vacíos.
     */
    public function test_assert_that_a_teacher_can_be_created_with_optional_fields_empty()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'ProfesorOpcional',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR98',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-002',
            'telephone' => '951 123 4568',
            'school_id' => $school->id
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => '¡Listo! Tus datos se guardaron bien.']);

        $this->post('api/logout');
    }

    // ============================================================
    // 2. PRUEBAS DE EDICIÓN
    // ============================================================

    /**
     * Afirmar que se puede editar un profesor.
     */
    public function test_assert_that_a_teacher_can_be_edited()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $teacher = Teacher::first();
        $school = $this->getTestSchool();

        $originalName = $teacher->name;
        $originalPaternalSurname = $teacher->paternal_surname;

        $updatedData = $this->getValidTeacherData($school->id);
        $updatedData['name'] = 'ProfesorEditado';
        $updatedData['paternal_surname'] = 'ApellidoEditado';
        $updatedData['curp'] = 'PROF800101HDFRRR88';
        $updatedData['rfc'] = 'PROF800101HDF';

        $response = $this->actingAs($userAdministrative)->putJson('api/teachers/' . $teacher->id, $updatedData);

        $response->assertStatus(200)
            ->assertJson(['message' => '¡Listo! Tus datos se guardaron bien.']);

        // Verificar en la base de datos
        $this->assertDatabaseHas('teachers', [
            'id' => $teacher->id,
            'name' => 'ProfesorEditado',
            'paternal_surname' => 'ApellidoEditado'
        ]);

        $this->post('api/logout');

        // Restaurar los datos originales
        $restoreData = $this->getValidTeacherData($school->id);
        $restoreData['name'] = $originalName;
        $restoreData['paternal_surname'] = $originalPaternalSurname;
        $restoreData['curp'] = $teacher->curp;
        $restoreData['rfc'] = $teacher->rfc;

        $response = $this->actingAs($userAdministrative)->putJson('api/teachers/' . $teacher->id, $restoreData);
        $response->assertStatus(200);

        $this->post('api/logout');
    }

    /**
     * Afirmar que no se puede editar un profesor sin autenticación.
     */
    public function test_assert_that_a_teacher_cannot_be_edited_without_authentication()
    {
        $teacher = Teacher::first();
        $school = $this->getTestSchool();
        $teacherData = $this->getValidTeacherData($school->id);

        $response = $this->putJson('api/teachers/' . $teacher->id, $teacherData);

        $response->assertStatus(401);
    }

    /**
     * Afirmar que no se puede editar un profesor sin permiso.
     */
    public function test_assert_that_a_teacher_cannot_be_edited_without_permission()
    {
        $userWithoutPermission = $this->getUserWithoutPermission();
        $teacher = Teacher::first();
        $school = $this->getTestSchool();
        $teacherData = $this->getValidTeacherData($school->id);

        $response = $this->actingAs($userWithoutPermission)->putJson('api/teachers/' . $teacher->id, $teacherData);

        $response->assertStatus(403);
        $this->post('api/logout');
    }

    /**
     * Afirmar que se puede editar un profesor con campos opcionales vacíos.
     */
    public function test_assert_that_a_teacher_can_be_edited_with_optional_fields_empty()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $teacher = Teacher::first();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->putJson('api/teachers/' . $teacher->id, [
            'name' => $teacher->name,
            'paternal_surname' => $teacher->paternal_surname,
            'curp' => $teacher->curp,
            'rfc' => $teacher->rfc,
            'gender' => $teacher->gender,
            'budget_code' => $teacher->budget_code,
            'telephone' => $teacher->telephone,
            'school_id' => $teacher->school_id
        ]);

        $response->assertStatus(200);
        $this->post('api/logout');
    }

    /**
     * Afirmar que no se puede editar un profesor que no existe.
     */
    public function test_assert_that_a_teacher_cannot_be_edited_that_does_not_exist()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();
        $nonExistentId = 999999;

        $response = $this->actingAs($userAdministrative)->putJson('api/teachers/' . $nonExistentId, [
            'name' => 'ProfesorInexistente',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(404);
        $this->post('api/logout');
    }

    // ============================================================
    // 3. PRUEBAS DE ELIMINACIÓN
    // ============================================================

    /**
     * Afirmar que se puede eliminar un profesor.
     */
    public function test_assert_that_a_teacher_can_be_deleted()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $administrativeHuman = $this->getAdministrativeHuman();
        $school = $this->getTestSchool();

        // Crear profesor para eliminar
        $teacher = Teacher::create([
            'name' => 'ProfesorEliminar',
            'paternal_surname' => 'ApellidoPaterno',
            'maternal_surname' => 'ApellidoMaterno',
            'curp' => 'PROF800101HDFRRR77',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-DEL-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id,
            'human_id' => $administrativeHuman->id
        ]);

        $response = $this->actingAs($userAdministrative)->deleteJson('api/teachers/' . $teacher->id);

        $response->assertStatus(200)
            ->assertJson(['message' => '¡Listo! Tu dato fue eliminado bien']);

        // Verificar que el campo deleted_at NO sea null (fue eliminado suavemente)
        $this->assertSoftDeleted('teachers', [
            'id' => $teacher->id
        ]);

        $this->post('api/logout');
    }

    /**
     * Afirmar que no se puede eliminar un profesor sin autenticación.
     */
    public function test_assert_that_a_teacher_cannot_be_deleted_without_authentication()
    {
        $teacher = Teacher::first();

        $response = $this->deleteJson('api/teachers/' . $teacher->id);

        $response->assertStatus(401);

        // Verificar que no fue eliminado
        $this->assertDatabaseHas('teachers', [
            'id' => $teacher->id,
            'deleted_at' => null
        ]);
    }

    /**
     * Afirmar que no se puede eliminar un profesor sin permiso.
     */
    public function test_assert_that_a_teacher_cannot_be_deleted_without_permission()
    {
        $userWithoutPermission = $this->getUserWithoutPermission();
        $teacher = Teacher::first();

        $response = $this->actingAs($userWithoutPermission)->deleteJson('api/teachers/' . $teacher->id);

        $response->assertStatus(403);

        // Verificar que no fue eliminado
        $this->assertDatabaseHas('teachers', [
            'id' => $teacher->id,
            'deleted_at' => null
        ]);

        $this->post('api/logout');
    }

    /**
     * Afirmar que no se puede eliminar un profesor que no existe.
     */
    public function test_assert_that_a_teacher_cannot_be_deleted_that_does_not_exist()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $nonExistentId = 999999;

        $response = $this->actingAs($userAdministrative)->deleteJson('api/teachers/' . $nonExistentId);

        $response->assertStatus(404);
        $this->post('api/logout');
    }

    // ============================================================
    // 3. PRUEBAS DE VER
    // ============================================================

    /**
     * Afirmar que no se puede visualizar un profesor sin autenticación.
     */
    public function test_assert_that_a_teacher_cannot_be_viewed_without_authentication()
    {
        $response = $this->getJson('api/teachers/10/');

        $response->assertStatus(401);
    }

    /**
     * Afirmar que no se puede visualizar un profesor sin permiso.
     */
    public function test_assert_that_a_teacher_cannot_be_viewed_without_permission()
    {
        $userWithoutPermission = $this->getUserWithoutPermission();

        $response = $this->actingAs($userWithoutPermission)->getJson('api/teachers/10/');

        $response->assertStatus(403);
        $this->post('api/logout');
    }

    // ============================================================
    // 5. PRUEBAS DE REGLAS DE NEGOCIO QUE SE DEFINIERON EN EL MANUAL DE REQUERIMIENTOS
    // ============================================================

    /**
     * Afirmar que el campo "name" es obligatorio.
     */
    public function test_assert_that_the_name_field_is_required()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "name" no acepta más de 20 caracteres.
     */
    public function test_assert_that_the_name_field_does_not_accept_more_than_20_characters()
    {
        /**
         * str_repeat()
         * ============
         *
         * Repite una cadena de texto un número determinado de veces.
         * Es una función nativa de PHP, disponible desde PHP 4 y mantenida en PHP 8.
         *
         * SINTAXIS:
         *   str_repeat(string $string, int $times): string
         *
         * PARÁMETROS:
         *   @param string $string  Cadena que se va a repetir.
         *   @param int    $times   Número de veces que se repetirá la cadena.
         *                          Debe ser mayor o igual a 0.
         *
         * VALOR DE RETORNO:
         *   @return string  Una nueva cadena con el contenido de $string repetido $times veces.
         *
         * COMPORTAMIENTO:
         *   - Si $times es 0, devuelve una cadena vacía ('').
         *   - Si $string está vacío, devuelve una cadena vacía.
         *   - No modifica la cadena original (las cadenas en PHP son inmutables).
         *
         * EXCEPCIONES (PHP 8):
         *   @throws ValueError  Si $times es negativo.
         *                       Mensaje: "str_repeat(): Argument #2 ($times) must be
         *                       greater than or equal to 0"
         *
         * EJEMPLOS:
         *   ✅ str_repeat('a', 21)   → "aaaaaaaaaaaaaaaaaaaaa" (21 caracteres)
         *   ✅ str_repeat('ab', 3)   → "ababab"
         *   ✅ str_repeat('x', 0)    → "" (cadena vacía)
         *   ✅ str_repeat('', 10)    → "" (cadena vacía)
         *   ❌ str_repeat('a', -1)   → Lanza ValueError en PHP 8
         *
         * CASOS DE USO COMUNES:
         *   - Generar líneas separadoras: str_repeat('-', 50)
         *   - Rellenar cadenas: str_pad() lo usa internamente
         *   - Crear patrones repetitivos: str_repeat('*-', 10)
         *   - Generar indentación o espacios: str_repeat(' ', 4)
         *
         * RENDIMIENTO:
         *   - Función nativa implementada en C, muy eficiente.
         *   - No requiere bucles manuales en PHP.
         *   - El coste es proporcional al tamaño del resultado (string * times).
         *
         * SEGURIDAD:
         *   - No presenta riesgos de inyección por sí misma.
         *   - Precaución: si $times es muy grande, puede consumir mucha memoria.
         *     Considera validar el valor antes de usarlo con entradas de usuario.
         *
         * @see https://www.php.net/manual/es/function.str-repeat.php
         * 
         * Chat en deepseek: https://chat.deepseek.com/share/flnsp1z5i9eqcuy8id
         */
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => str_repeat('a', 21),
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "name" no acepta números.
     */
    public function test_assert_that_the_name_field_does_not_accept_numbers()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor123',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "paternal_surname" es obligatorio.
     */
    public function test_assert_that_the_paternal_surname_field_is_required()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['paternal_surname']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "paternal_surname" no acepta más de 20 caracteres.
     */
    public function test_assert_that_the_paternal_surname_field_does_not_accept_more_than_20_characters()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => str_repeat('a', 21),
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['paternal_surname']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "paternal_surname" no acepta números.
     */
    public function test_assert_that_the_paternal_surname_field_does_not_accept_numbers()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'Apellido123',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['paternal_surname']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "maternal_surname" no es obligatorio.
     */
    public function test_assert_that_the_maternal_surname_field_is_not_required()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => '¡Listo! Tus datos se guardaron bien.']);
        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "maternal_surname" no acepta más de 20 caracteres.
     */
    public function test_assert_that_the_maternal_surname_field_does_not_accept_more_than_20_characters()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'maternal_surname' => str_repeat('a', 21),
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['maternal_surname']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "maternal_surname" no acepta números.
     */
    public function test_assert_that_the_maternal_surname_field_does_not_accept_numbers()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'maternal_surname' => 'Apellido123',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['maternal_surname']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "curp" es obligatorio.
     */
    public function test_assert_that_the_curp_field_is_required()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['curp']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "curp" no acepta más de 18 caracteres.
     */
    public function test_assert_that_the_curp_field_does_not_accept_more_than_18_characters()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => str_repeat('A', 19),
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['curp']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "curp" solo acepta el patrón regex especificado.
     */
    public function test_assert_that_the_curp_field_only_accepts_the_specified_regex_pattern()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'CURPINCORRECTA123',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['curp']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "rfc" es obligatorio.
     */
    public function test_assert_that_the_rfc_field_is_required()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['rfc']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "rfc" no acepta más de 13 caracteres.
     */
    public function test_assert_that_the_rfc_field_does_not_accept_more_than_13_characters()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => str_repeat('A', 14),
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['rfc']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "rfc" solo acepta el patrón regex especificado.
     */
    public function test_assert_that_the_rfc_field_only_accepts_the_specified_regex_pattern()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'RFCINCORRECTO',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['rfc']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "gender" es obligatorio.
     */
    public function test_assert_that_the_gender_field_is_required()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['gender']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "gender" solo acepta los valores "Hombre" y "Mujer".
     */
    public function test_assert_that_the_gender_field_only_accepts_specific_values()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Otro',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['gender']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "budget_code" es obligatorio.
     */
    public function test_assert_that_the_budget_code_field_is_required()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['budget_code']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "budget_code" no acepta más de 23 caracteres.
     */
    public function test_assert_that_the_budget_code_field_does_not_accept_more_than_23_characters()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => str_repeat('A', 24),
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['budget_code']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "funcion" no es obligatorio.
     */
    public function test_assert_that_the_funcion_field_is_not_required()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => '¡Listo! Tus datos se guardaron bien.']);
        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "funcion" solo acepta los valores especificados.
     */
    public function test_assert_that_the_funcion_field_only_accepts_specific_values()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'funcion' => 'FuncionInvalida',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['funcion']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "school_id" es obligatorio.
     */
    public function test_assert_that_the_school_id_field_is_required()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['school_id']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "school_id" solo acepta índices existentes.
     */
    public function test_assert_that_the_school_id_field_only_accepts_existing_indices()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => 999999
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['school_id']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "telephone" es obligatorio.
     */
    public function test_assert_that_the_telephone_field_is_required()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['telephone']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "telephone" no acepta más de 10 dígitos.
     */
    public function test_assert_that_the_telephone_field_does_not_accept_more_than_10_digits()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 45678',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['telephone']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "telephone" solo acepta el patrón regex especificado.
     */
    public function test_assert_that_the_telephone_field_only_accepts_the_specified_regex_pattern()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '9511234567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['telephone']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "motivo" no es obligatorio.
     */
    public function test_assert_that_the_motivo_field_is_not_required()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => '¡Listo! Tus datos se guardaron bien.']);
        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "motivo" no acepta letras.
     */
    public function test_assert_that_the_motivo_field_does_not_accept_letters()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'motivo' => 'abc',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['motivo']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "motivo" sea menor a 100.
     */
    public function test_assert_that_the_motivo_field_is_less_than_100()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        // Probar número válido (menor a 100)
        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'motivo' => 99,
            'school_id' => $school->id
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => '¡Listo! Tus datos se guardaron bien.']);

        // Probar número inválido (mayor a 99)
        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR02',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-002',
            'telephone' => '951 123 4568',
            'motivo' => 100,
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['motivo']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "date_of_entry_into_the_sep" no es obligatorio.
     */
    public function test_assert_that_the_date_of_entry_into_the_sep_field_is_not_required()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(200);
        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "date_of_entry_into_the_sep" acepta el formato d/m/Y.
     */
    public function test_assert_that_the_date_of_entry_into_the_sep_field_accepts_the_d_m_Y_format()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'date_of_entry_into_the_sep' => '15/03/2020',
            'school_id' => $school->id
        ]);

        $response->assertStatus(200);
        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "date_of_entry_into_the_sep" no acepta un formato incorrecto.
     */
    public function test_assert_that_the_date_of_entry_into_the_sep_field_does_not_accept_an_incorrect_format()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'date_of_entry_into_the_sep' => '2020-03-15',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['date_of_entry_into_the_sep']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "study_profile" no es obligatorio.
     */
    public function test_assert_that_the_study_profile_field_is_not_required()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(200);
        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "study_profile" solo acepta los valores especificados.
     */
    public function test_assert_that_the_study_profile_field_only_accepts_specific_values()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'study_profile' => 'PerfilInvalido',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['study_profile']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "language" no es obligatorio.
     */
    public function test_assert_that_the_language_field_is_not_required()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(200);
        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "language" solo acepta los valores especificados.
     */
    public function test_assert_that_the_language_field_only_accepts_specific_values()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'language' => 'LenguaInvalida',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['language']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "language_variant" no es obligatorio.
     */
    public function test_assert_that_the_language_variant_field_is_not_required()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'school_id' => $school->id
        ]);

        $response->assertStatus(200);
        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "language_variant" solo acepta los valores "Alta" y "Baja".
     */
    public function test_assert_that_the_language_variant_field_only_accepts_specific_values()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', [
            'name' => 'Profesor',
            'paternal_surname' => 'ApellidoPaterno',
            'curp' => 'PROF800101HDFRRR01',
            'rfc' => 'PROF800101HDF',
            'gender' => 'Hombre',
            'budget_code' => 'BUD-2024-TEST-001',
            'telephone' => '951 123 4567',
            'language_variant' => 'Media',
            'school_id' => $school->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['language_variant']);

        $this->post('api/logout');
    }

    // ============================================================
    // 6. PRUEBAS DE LISTADO Y PAGINACIÓN
    // ============================================================

    /**
     * Afirmar que al obtener profesores tiene paginación.
     */
    public function test_assert_that_obtaining_teachers_has_pagination()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'current_page',
                'data' => [
                    '*' => [
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
                        'school' => ['id', 'name']
                    ]
                ],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links' => [
                    '*' => ['url', 'label', 'active']
                ],
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total'
            ]);

        // Verificar que tiene 10 registros por defecto
        $this->assertCount(10, $response->json('data'));

        $this->post('api/logout');
    }

    /**
     * Afirmar ordenamiento descendente por ID.
     */
    public function test_assert_descending_order_by_id()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/');

        $data = $response->json('data');

        // Verificar que el primer ID es mayor que el segundo (descendente)
        if (count($data) >= 2) {
            $this->assertGreaterThan($data[1]['id'], $data[0]['id']);
        }

        $this->post('api/logout');
    }

    /**
     * Afirmar que los profesores son paginados correctamente por 10 registros.
     */
    public function test_assert_that_teachers_are_correctly_paginated_by_10_records()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/');

        $this->assertEquals(10, $response->json('per_page'));
        $this->assertCount(10, $response->json('data'));

        $this->post('api/logout');
    }

    /**
     * Afirmar que se consultan todos los profesores al seleccionar todos.
     */
    public function test_assert_that_all_teachers_are_consulted_when_selecting_all()
    {
        $userAdministrative = $this->getAdministrativeUser();

        // Contar total de profesores
        $totalTeachers = Teacher::count();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/' . $totalTeachers . '/');

        $data = $response->json('data');
        $this->assertEquals($totalTeachers, count($data));

        $this->post('api/logout');
    }

    // ============================================================
    // 7. PRUEBAS DE BÚSQUEDA
    // ============================================================

    /**
     * Afirmar que la búsqueda por "name" es buena.
     */
    public function test_assert_that_searching_by_name_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/Pedro');

        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertGreaterThan(0, count($data));
        $this->assertEquals('Pedro', $data[0]['name']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "name" en mayúsculas y minúsculas es buena.
     */
    public function test_assert_that_searching_by_name_with_uppercase_and_lowercase_letters_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        // Búsqueda en minúsculas
        $responseLower = $this->actingAs($userAdministrative)->getJson('api/teachers/10/pedro');
        $responseLower->assertStatus(200);
        $this->assertGreaterThan(0, count($responseLower->json('data')));

        // Búsqueda en mayúsculas
        $responseUpper = $this->actingAs($userAdministrative)->getJson('api/teachers/10/PEDRO');
        $responseUpper->assertStatus(200);
        $this->assertGreaterThan(0, count($responseUpper->json('data')));

        $this->post('api/logout');
    }

    /**
     * Afirmar que al buscar un "name" que no existe, no regresa nada.
     */
    public function test_assert_that_searching_by_name_that_does_not_exist_returns_nothing()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/NombreInexistente');

        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
        $this->assertEquals(0, $response->json('total'));

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "paternal_surname" es buena.
     */
    public function test_assert_that_searching_by_paternal_surname_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/Mejia');

        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertGreaterThan(0, count($data));
        $this->assertEquals('Mejía', $data[0]['paternal_surname']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "paternal_surname" en mayúsculas y minúsculas es buena.
     */
    public function test_assert_that_searching_by_paternal_surname_with_uppercase_and_lowercase_letters_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        // Búsqueda en minúsculas
        $responseLower = $this->actingAs($userAdministrative)->getJson('api/teachers/10/mejía');
        $responseLower->assertStatus(200);
        $this->assertGreaterThan(0, count($responseLower->json('data')));

        // Búsqueda en mayúsculas
        $responseUpper = $this->actingAs($userAdministrative)->getJson('api/teachers/10/MEJÍA');
        $responseUpper->assertStatus(200);
        $this->assertGreaterThan(0, count($responseUpper->json('data')));

        $this->post('api/logout');
    }

    /**
     * Afirmar que al buscar por un "paternal_surname" que no existe, no regresa nada.
     */
    public function test_assert_that_searching_by_paternal_surname_that_does_not_exist_returns_nothing()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/ApellidoInexistente');

        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
        $this->assertEquals(0, $response->json('total'));

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "maternal_surname" es buena.
     * 
     * Tuve que convertir el resultado en una colección, debido a que retorno dos registros.
     * Hasta ahí todo bien, pero como el campo maternal_surname es opcional, el primer registro viene vacio.
     * Como estoy comparando el primer registro por eso debo asegurarme que es la primera fila.
     */
    public function test_assert_that_searching_by_maternal_surname_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/Caballero');

        $response->assertStatus(200);
         $data = collect($response->json('data'));

        $this->assertGreaterThan(0, $data->count());

        // Filtra solo los que tienen maternal_surname = 'Caballero'
        $conApellido = $data->where('maternal_surname', 'Caballero')->values();

        // Y el primero de los filtrados debe tener el apellido correcto
        $this->assertEquals('Caballero', $data[0]['maternal_surname']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "maternal_surname" en mayúsculas y minúsculas es buena.
     */
    public function test_assert_that_searching_by_maternal_surname_with_uppercase_and_lowercase_letters_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        // Búsqueda en minúsculas
        $responseLower = $this->actingAs($userAdministrative)->getJson('api/teachers/10/caballero');
        $responseLower->assertStatus(200);
        $this->assertGreaterThan(0, count($responseLower->json('data')));

        // Búsqueda en mayúsculas
        $responseUpper = $this->actingAs($userAdministrative)->getJson('api/teachers/10/CABALLERO');
        $responseUpper->assertStatus(200);
        $this->assertGreaterThan(0, count($responseUpper->json('data')));

        $this->post('api/logout');
    }

    /**
     * Afirmar que al buscar por un "maternal_surname" que no existe, no regresa nada.
     */
    public function test_assert_that_searching_by_maternal_surname_that_does_not_exist_returns_nothing()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/MaternoInexistente');

        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
        $this->assertEquals(0, $response->json('total'));

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "curp" es buena.
     */
    public function test_assert_that_searching_by_curp_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/GALM800101HMCLRR01');

        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertGreaterThan(0, count($data));
        $this->assertEquals('GALM800101HMCLRR01', $data[0]['curp']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "curp" en mayúsculas y minúsculas es buena.
     */
    public function test_assert_that_searching_by_curp_with_uppercase_and_lowercase_letters_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        // Búsqueda en minúsculas
        $responseLower = $this->actingAs($userAdministrative)->getJson('api/teachers/10/galm800101hmclrr01');
        $responseLower->assertStatus(200);
        $this->assertGreaterThan(0, count($responseLower->json('data')));

        // Búsqueda en mayúsculas
        $responseUpper = $this->actingAs($userAdministrative)->getJson('api/teachers/10/GALM800101HMCLRR01');
        $responseUpper->assertStatus(200);
        $this->assertGreaterThan(0, count($responseUpper->json('data')));

        $this->post('api/logout');
    }

    /**
     * Afirmar que al buscar por una "curp" que no existe, no regresa nada.
     */
    public function test_assert_that_searching_by_curp_that_does_not_exist_returns_nothing()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/CURPINEXISTENTE123');

        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
        $this->assertEquals(0, $response->json('total'));

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "rfc" es buena.
     */
    public function test_assert_that_searching_by_rfc_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/GALM800101HCL');

        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertGreaterThan(0, count($data));
        $this->assertEquals('GALM800101HCL', $data[0]['rfc']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "rfc" en mayúsculas y minúsculas es buena.
     */
    public function test_assert_that_searching_by_rfc_with_uppercase_and_lowercase_letters_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        // Búsqueda en minúsculas
        $responseLower = $this->actingAs($userAdministrative)->getJson('api/teachers/10/galm800101hcl');
        $responseLower->assertStatus(200);
        $this->assertGreaterThan(0, count($responseLower->json('data')));

        // Búsqueda en mayúsculas
        $responseUpper = $this->actingAs($userAdministrative)->getJson('api/teachers/10/GALM800101HCL');
        $responseUpper->assertStatus(200);
        $this->assertGreaterThan(0, count($responseUpper->json('data')));

        $this->post('api/logout');
    }

    /**
     * Afirmar que al buscar por una "rfc" que no existe, no regresa nada.
     */
    public function test_assert_that_searching_by_rfc_that_does_not_exist_returns_nothing()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/RFCINEXISTENTE');

        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
        $this->assertEquals(0, $response->json('total'));

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "gender" es buena.
     */
    public function test_assert_that_searching_by_gender_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/Hombre');

        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertGreaterThan(0, count($data));
        $this->assertEquals('Hombre', $data[0]['gender']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "gender" en mayúsculas y minúsculas es buena.
     */
    public function test_assert_that_searching_by_gender_with_uppercase_and_lowercase_letters_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        // Búsqueda en minúsculas
        $responseLower = $this->actingAs($userAdministrative)->getJson('api/teachers/10/hombre');
        $responseLower->assertStatus(200);
        $this->assertGreaterThan(0, count($responseLower->json('data')));

        // Búsqueda en mayúsculas
        $responseUpper = $this->actingAs($userAdministrative)->getJson('api/teachers/10/HOMBRE');
        $responseUpper->assertStatus(200);
        $this->assertGreaterThan(0, count($responseUpper->json('data')));

        $this->post('api/logout');
    }

    /**
     * Afirmar que al buscar por un "gender" que no existe, no regresa nada.
     */
    public function test_assert_that_searching_by_gender_that_does_not_exist_returns_nothing()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/Otro');

        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
        $this->assertEquals(0, $response->json('total'));

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "budget_code" es buena.
     */
    public function test_assert_that_searching_by_budget_code_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/BUD-2024-001-001-001-01');

        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertGreaterThan(0, count($data));
        $this->assertEquals('BUD-2024-001-001-001-01', $data[0]['budget_code']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "budget_code" en mayúsculas y minúsculas es buena.
     */
    public function test_assert_that_searching_by_budget_code_with_uppercase_and_lowercase_letters_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        // Búsqueda en minúsculas
        $responseLower = $this->actingAs($userAdministrative)->getJson('api/teachers/10/bud-2024-001-001-001-01');
        $responseLower->assertStatus(200);
        $this->assertGreaterThan(0, count($responseLower->json('data')));

        // Búsqueda en mayúsculas
        $responseUpper = $this->actingAs($userAdministrative)->getJson('api/teachers/10/BUD-2024-001-001-001-01');
        $responseUpper->assertStatus(200);
        $this->assertGreaterThan(0, count($responseUpper->json('data')));

        $this->post('api/logout');
    }

    /**
     * Afirmar que al buscar por una "budget_code" que no existe, no regresa nada.
     */
    public function test_assert_that_searching_by_budget_code_that_does_not_exist_returns_nothing()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/BUD-INEXISTENTE');

        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
        $this->assertEquals(0, $response->json('total'));

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "funcion" es buena.
     */
    public function test_assert_that_searching_by_funcion_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/Director');

        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertGreaterThan(0, count($data));
        $this->assertEquals('Director', $data[0]['funcion']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "funcion" en mayúsculas y minúsculas es buena.
     */
    public function test_assert_that_searching_by_funcion_with_uppercase_and_lowercase_letters_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        // Búsqueda en minúsculas
        $responseLower = $this->actingAs($userAdministrative)->getJson('api/teachers/10/director');
        $responseLower->assertStatus(200);
        $this->assertGreaterThan(0, count($responseLower->json('data')));

        // Búsqueda en mayúsculas
        $responseUpper = $this->actingAs($userAdministrative)->getJson('api/teachers/10/DIRECTOR');
        $responseUpper->assertStatus(200);
        $this->assertGreaterThan(0, count($responseUpper->json('data')));

        $this->post('api/logout');
    }

    /**
     * Afirmar que al buscar por una "funcion" que no existe, no regresa nada.
     */
    public function test_assert_that_searching_by_funcion_that_does_not_exist_returns_nothing()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/FuncionInexistente');

        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
        $this->assertEquals(0, $response->json('total'));

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "telephone" es buena.
     */
    public function test_assert_that_searching_by_telephone_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/951 526 5683');

        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertGreaterThan(0, count($data));
        $this->assertEquals('951 526 5683', $data[0]['telephone']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que al buscar por un "telephone" que no existe, no regresa nada.
     */
    public function test_assert_that_searching_by_telephone_that_does_not_exist_returns_nothing()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/000 000 0000');

        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
        $this->assertEquals(0, $response->json('total'));

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "motivo" es buena.
     */
    public function test_assert_that_searching_by_motivo_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/1');

        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertGreaterThan(0, count($data));
        $this->assertEquals(1, $data[0]['motivo']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que al buscar por un "motivo" que no existe, no regresa nada.
     */
    public function test_assert_that_searching_by_motivo_that_does_not_exist_returns_nothing()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/99');

        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
        $this->assertEquals(0, $response->json('total'));

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "date_of_entry_into_the_sep" es buena.
     */
    public function test_assert_that_searching_by_date_of_entry_into_the_sep_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/30/08/1966');

        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertGreaterThan(0, count($data));

        $this->post('api/logout');
    }

    /**
     * Afirmar que al buscar por una "date_of_entry_into_the_sep" que no existe, no regresa nada.
     */
    public function test_assert_that_searching_by_date_of_entry_into_the_sep_that_does_not_exist_returns_nothing()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/01/01/1900');

        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
        $this->assertEquals(0, $response->json('total'));

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "study_profile" es buena.
     */
    public function test_assert_that_searching_by_study_profile_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/Titulado de U.P.N.');

        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertGreaterThan(0, count($data));
        $this->assertEquals('Titulado de U.P.N.', $data[0]['study_profile']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "study_profile" en mayúsculas y minúsculas es buena.
     */
    public function test_assert_that_searching_by_study_profile_with_uppercase_and_lowercase_letters_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        // Búsqueda en minúsculas
        $responseLower = $this->actingAs($userAdministrative)->getJson('api/teachers/10/titulado de u.p.n.');
        $responseLower->assertStatus(200);
        $this->assertGreaterThan(0, count($responseLower->json('data')));

        // Búsqueda en mayúsculas
        $responseUpper = $this->actingAs($userAdministrative)->getJson('api/teachers/10/TITULADO DE U.P.N.');
        $responseUpper->assertStatus(200);
        $this->assertGreaterThan(0, count($responseUpper->json('data')));

        $this->post('api/logout');
    }

    /**
     * Afirmar que al buscar por un "study_profile" que no existe, no regresa nada.
     */
    public function test_assert_that_searching_by_study_profile_that_does_not_exist_returns_nothing()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/PerfilInexistente');

        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
        $this->assertEquals(0, $response->json('total'));

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "language" es buena.
     */
    public function test_assert_that_searching_by_language_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/Mixteca');

        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertGreaterThan(0, count($data));
        $this->assertEquals('Mixteca', $data[0]['language']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "language" en mayúsculas y minúsculas es buena.
     */
    public function test_assert_that_searching_by_language_with_uppercase_and_lowercase_letters_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        // Búsqueda en minúsculas
        $responseLower = $this->actingAs($userAdministrative)->getJson('api/teachers/10/mixteca');
        $responseLower->assertStatus(200);
        $this->assertGreaterThan(0, count($responseLower->json('data')));

        // Búsqueda en mayúsculas
        $responseUpper = $this->actingAs($userAdministrative)->getJson('api/teachers/10/MIXTECA');
        $responseUpper->assertStatus(200);
        $this->assertGreaterThan(0, count($responseUpper->json('data')));

        $this->post('api/logout');
    }

    /**
     * Afirmar que al buscar por una "language" que no existe, no regresa nada.
     */
    public function test_assert_that_searching_by_language_that_does_not_exist_returns_nothing()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/LenguaInexistente');

        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
        $this->assertEquals(0, $response->json('total'));

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "language_variant" es buena.
     */
    public function test_assert_that_searching_by_language_variant_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/Alta');

        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertGreaterThan(0, count($data));
        $this->assertEquals('Alta', $data[0]['language_variant']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "language_variant" en mayúsculas y minúsculas es buena.
     */
    public function test_assert_that_searching_by_language_variant_with_uppercase_and_lowercase_letters_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();

        // Búsqueda en minúsculas
        $responseLower = $this->actingAs($userAdministrative)->getJson('api/teachers/10/alta');
        $responseLower->assertStatus(200);
        $this->assertGreaterThan(0, count($responseLower->json('data')));

        // Búsqueda en mayúsculas
        $responseUpper = $this->actingAs($userAdministrative)->getJson('api/teachers/10/ALTA');
        $responseUpper->assertStatus(200);
        $this->assertGreaterThan(0, count($responseUpper->json('data')));

        $this->post('api/logout');
    }

    /**
     * Afirmar que al buscar por una "language_variant" que no existe, no regresa nada.
     */
    public function test_assert_that_searching_by_language_variant_that_does_not_exist_returns_nothing()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/Media');

        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
        $this->assertEquals(0, $response->json('total'));

        $this->post('api/logout');
    }

    // ============================================================
    // 8. PRUEBAS DE RELACIONES
    // ============================================================

    /**
     * Afirmar que al obtener profesor, incluye la relación con escuela.
     */
    public function test_assert_that_when_obtaining_teacher_it_includes_the_relationship_with_school()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/');

        $response->assertStatus(200);
        $data = $response->json('data');

        if (count($data) > 0) {
            $this->assertArrayHasKey('school', $data[0]);
            $this->assertArrayHasKey('id', $data[0]['school']);
            $this->assertArrayHasKey('name', $data[0]['school']);
        }

        $this->post('api/logout');
    }

    /**
     * Afirmar que al crear profesor con school_id, verificar que la relación se establece.
     */
    public function test_assert_that_when_creating_teacher_with_school_id_the_relationship_is_established()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = $this->getTestSchool();

        $teacherData = $this->getValidTeacherData($school->id);
        $teacherData['name'] = 'ProfesorRelacion';
        $teacherData['curp'] = 'PROF800101HDFRRR66';

        $response = $this->actingAs($userAdministrative)->postJson('api/teachers/store', $teacherData);

        $response->assertStatus(200);

        // Verificar que la relación se estableció correctamente
        $teacher = Teacher::where('name', 'ProfesorRelacion')->first();
        $this->assertEquals($school->id, $teacher->school_id);
        $this->assertEquals($school->name, $teacher->school->name);

        $this->post('api/logout');
    }

    // ============================================================
    // 9. PRUEBAS DE ESTRUCTURA DE RESPUESTA
    // ============================================================

    /**
     * Afirmar que la respuesta JSON tiene la estructura esperada.
     */
    public function test_assert_that_the_json_response_has_the_expected_structure()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
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
                        'school' => ['id', 'name']
                    ]
                ]
            ]);

        $this->post('api/logout');
    }

    /**
     * Afirmar que los campos sensibles no existen en la respuesta
     */
    public function test_assert_state_that_sensitive_fields_do_not_exist_in_the_response()
    {
        $userAdministrative = $this->getAdministrativeUser();

        $response = $this->actingAs($userAdministrative)->getJson('api/teachers/10/');

        $response->assertStatus(200);
        $data = $response->json('data');

        if (count($data) > 0) {
            $teacher = $data[0];

            // Verificar que NO existen campos sensibles
            $this->assertArrayNotHasKey('human_id', $teacher);
            $this->assertArrayNotHasKey('deleted_at', $teacher);
            $this->assertArrayNotHasKey('created_at', $teacher);
            $this->assertArrayNotHasKey('updated_at', $teacher);
        }

        $this->post('api/logout');
    }
}