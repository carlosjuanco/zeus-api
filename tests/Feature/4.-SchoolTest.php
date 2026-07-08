<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Human;
use App\Models\School;
use App\Models\Community;

class SchoolTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Configuración inicial para todas las pruebas
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Ejecutar seeders necesarios
        $this->seed();
    }

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
     * Helper para obtener una comunidad existente para pruebas
     */
    private function getTestCommunity()
    {
        return Community::first();
    }

    // ============================================================
    // 1. PRUEBAS DE AUTENTICACIÓN Y PERMISOS
    // ============================================================

    /**
     * Afirmar que no se puede crear una escuela sin autenticación.
     */
    public function test_assert_that_a_school_cannot_be_created_without_authentication()
    {
        $community = $this->getTestCommunity();
        
        $response = $this->postJson('api/schools/store', [
            'name' => 'Escuela Test',
            'key' => 'TEST123',
            'type_of_school' => 'Primaria',
            'community_id' => $community->id,
            'secondary_number' => 5
        ]);
        
        $response->assertStatus(401);
    }

    /**
     * Afirmar que no se puede crear una escuela sin permiso.
     */
    public function test_assert_that_a_school_cannot_be_created_without_permission()
    {
        $userWithoutPermission = $this->getUserWithoutPermission();
        $community = $this->getTestCommunity();
        
        $response = $this->actingAs($userWithoutPermission)->postJson('api/schools/store', [
            'name' => 'Escuela Test',
            'key' => 'TEST123',
            'type_of_school' => 'Primaria',
            'community_id' => $community->id,
            'secondary_number' => 5
        ]);
        
        $response->assertStatus(403);
        $this->post('api/logout');
    }

    /**
     * Afirmar que no se puede editar una escuela sin autenticación.
     */
    public function test_assert_that_a_school_cannot_be_edited_without_authentication()
    {
        $school = School::first();
        
        $response = $this->putJson('api/schools/' . $school->id, [
            'name' => 'Escuela Editada',
            'type_of_school' => 'Primaria',
            'community_id' => $school->community_id
        ]);
        
        $response->assertStatus(401);
    }

    /**
     * Afirmar que no se puede editar una escuela sin permiso.
     */
    public function test_assert_that_a_school_cannot_be_edited_without_permission()
    {
        $userWithoutPermission = $this->getUserWithoutPermission();
        $school = School::first();
        
        $response = $this->actingAs($userWithoutPermission)->putJson('api/schools/' . $school->id, [
            'name' => 'Escuela Editada',
            'type_of_school' => 'Primaria',
            'community_id' => $school->community_id
        ]);
        
        $response->assertStatus(403);
        $this->post('api/logout');
    }

    /**
     * Afirmar que no se puede eliminar una escuela sin autenticación.
     */
    public function test_assert_that_a_school_cannot_be_deleted_without_authentication()
    {
        $school = School::first();
        
        $response = $this->deleteJson('api/schools/' . $school->id);
        
        $response->assertStatus(401);
        
        // Verificar que no fue eliminada
        $this->assertDatabaseHas('schools', [
            'id' => $school->id,
            'deleted_at' => null
        ]);
    }

    /**
     * Afirmar que no se puede eliminar una escuela sin permiso.
     */
    public function test_assert_that_a_school_cannot_be_deleted_without_permission()
    {
        $userWithoutPermission = $this->getUserWithoutPermission();
        $school = School::first();
        
        $response = $this->actingAs($userWithoutPermission)->deleteJson('api/schools/' . $school->id);
        
        $response->assertStatus(403);
        
        // Verificar que no fue eliminada
        $this->assertDatabaseHas('schools', [
            'id' => $school->id,
            'deleted_at' => null
        ]);
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que no se puede visualizar una escuela sin autenticación.
     */
    public function test_assert_that_a_school_cannot_be_viewed_without_authentication()
    {
        $response = $this->getJson('api/schools/10/');
        
        $response->assertStatus(401);
    }

    /**
     * Afirmar que no se puede visualizar una escuela sin permiso.
     */
    public function test_assert_that_a_school_cannot_be_viewed_without_permission()
    {
        $userWithoutPermission = $this->getUserWithoutPermission();
        
        $response = $this->actingAs($userWithoutPermission)->getJson('api/schools/10/');
        
        $response->assertStatus(403);
        $this->post('api/logout');
    }

    // ============================================================
    // 2. PRUEBAS DE CREACIÓN
    // ============================================================

    /**
     * Afirmar que se puede crear una escuela.
     */
    public function test_assert_that_a_school_can_be_created()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $administrativeHuman = $this->getAdministrativeHuman();
        $community = $this->getTestCommunity();
        
        $schoolData = [
            'name' => 'Escuela Nueva',
            'key' => 'ESC001',
            'type_of_school' => 'Primaria',
            'community_id' => $community->id,
            'secondary_number' => 5
        ];
        
        $response = $this->actingAs($userAdministrative)->postJson('api/schools/store', $schoolData);
        
        $response->assertStatus(200)
            ->assertJson(['message' => '¡Listo! Tus datos se guardaron bien.']);
        
        // Verificar que la nueva escuela fue creada en la base de datos
        $this->assertDatabaseHas('schools', [
            'name' => 'Escuela Nueva',
            'key' => 'ESC001',
            'type_of_school' => 'Primaria',
            'community_id' => $community->id,
            'secondary_number' => 5,
            'human_id' => $administrativeHuman->id
        ]);
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que se puede crear una escuela con campos opcionales vacíos.
     */
    public function test_assert_that_a_school_can_be_created_with_optional_fields_empty()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $community = $this->getTestCommunity();
        
        $response = $this->actingAs($userAdministrative)->postJson('api/schools/store', [
            'type_of_school' => 'Primaria',
            'community_id' => $community->id
        ]);
        
        $response->assertStatus(200)
            ->assertJson(['message' => '¡Listo! Tus datos se guardaron bien.']);
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "name" no es obligatorio.
     */
    public function test_assert_that_the_name_field_is_not_required()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $community = $this->getTestCommunity();
        
        $response = $this->actingAs($userAdministrative)->postJson('api/schools/store', [
            'type_of_school' => 'Primaria',
            'community_id' => $community->id
        ]);
        
        $response->assertStatus(200);
        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "name" no acepta más de 26 caracteres.
     */
    public function test_assert_that_the_name_field_does_not_accept_more_than_26_characters()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $community = $this->getTestCommunity();
        
        $response = $this->actingAs($userAdministrative)->postJson('api/schools/store', [
            'name' => str_repeat('a', 27),
            'type_of_school' => 'Primaria',
            'community_id' => $community->id
        ]);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "key" no es obligatorio.
     */
    public function test_assert_that_the_key_field_is_not_required()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $community = $this->getTestCommunity();
        
        $response = $this->actingAs($userAdministrative)->postJson('api/schools/store', [
            'type_of_school' => 'Primaria',
            'community_id' => $community->id
        ]);
        
        $response->assertStatus(200);
        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "key" no acepta más de 10 caracteres.
     */
    public function test_assert_that_the_key_field_does_not_accept_more_than_10_characters()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $community = $this->getTestCommunity();
        
        $response = $this->actingAs($userAdministrative)->postJson('api/schools/store', [
            'key' => str_repeat('A', 11),
            'type_of_school' => 'Primaria',
            'community_id' => $community->id
        ]);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['key']);
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "type_of_school" es obligatorio.
     */
    public function test_assert_that_the_type_of_school_field_is_required()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $community = $this->getTestCommunity();
        
        $response = $this->actingAs($userAdministrative)->postJson('api/schools/store', [
            'community_id' => $community->id
        ]);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['type_of_school']);
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "type_of_school" solo acepta valores específicos.
     */
    public function test_assert_that_the_type_of_school_field_only_accepts_specific_values()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $community = $this->getTestCommunity();
        
        $invalidTypes = ['Secundaria', 'Bachillerato', 'Universidad'];
        
        foreach ($invalidTypes as $invalidType) {
            $response = $this->actingAs($userAdministrative)->postJson('api/schools/store', [
                'type_of_school' => $invalidType,
                'community_id' => $community->id
            ]);
            
            $response->assertStatus(422)
                ->assertJsonValidationErrors(['type_of_school']);
        }
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "community_id" es obligatorio.
     */
    public function test_assert_that_the_community_id_field_is_required()
    {
        $userAdministrative = $this->getAdministrativeUser();
        
        $response = $this->actingAs($userAdministrative)->postJson('api/schools/store', [
            'type_of_school' => 'Primaria'
        ]);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['community_id']);
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "community_id" solo acepta índices existentes.
     */
    public function test_assert_that_the_community_id_field_only_accepts_existing_indices()
    {
        $userAdministrative = $this->getAdministrativeUser();
        
        $response = $this->actingAs($userAdministrative)->postJson('api/schools/store', [
            'type_of_school' => 'Primaria',
            'community_id' => 999999 // ID inexistente
        ]);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['community_id']);
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "secondary_number" no es obligatorio.
     */
    public function test_assert_that_the_secondary_number_field_is_not_required()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $community = $this->getTestCommunity();
        
        $response = $this->actingAs($userAdministrative)->postJson('api/schools/store', [
            'type_of_school' => 'Primaria',
            'community_id' => $community->id
        ]);
        
        $response->assertStatus(200);
        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "secondary_number" no acepta letras.
     */
    public function test_assert_that_the_secondary_number_field_does_not_accept_letters()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $community = $this->getTestCommunity();
        
        $response = $this->actingAs($userAdministrative)->postJson('api/schools/store', [
            'type_of_school' => 'Primaria',
            'community_id' => $community->id,
            'secondary_number' => 'abc'
        ]);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['secondary_number']);
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "secondary_number" debe ser mayor a 9 (válido: 0-9).
     */
    public function test_assert_that_the_secondary_number_field_must_be_greater_than_9()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $community = $this->getTestCommunity();
        
        // Probar números válidos (0-9)
        for ($i = 0; $i <= 9; $i++) {
            $response = $this->actingAs($userAdministrative)->postJson('api/schools/store', [
                'type_of_school' => 'Primaria',
                'community_id' => $community->id,
                'secondary_number' => $i
            ]);
            
            $response->assertStatus(200);
        }
        
        // Probar número inválido (10)
        $response = $this->actingAs($userAdministrative)->postJson('api/schools/store', [
            'type_of_school' => 'Primaria',
            'community_id' => $community->id,
            'secondary_number' => 10
        ]);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['secondary_number']);
        
        $this->post('api/logout');
    }

    // ============================================================
    // 3. PRUEBAS DE EDICIÓN
    // ============================================================

    /**
     * Afirmar que se puede editar una escuela.
     */
    public function test_assert_that_a_school_can_be_edited()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = School::first();
        $community = $this->getTestCommunity();
        
        $updatedData = [
            'name' => 'Escuela Editada',
            'key' => 'EDIT001',
            'type_of_school' => 'Primaria',
            'community_id' => $community->id,
            'secondary_number' => 8
        ];
        
        $response = $this->actingAs($userAdministrative)->putJson('api/schools/' . $school->id, $updatedData);
        
        $response->assertStatus(200)
            ->assertJson(['message' => '¡Listo! Tus datos se guardaron bien.']);
        
        // Verificar en la base de datos
        $this->assertDatabaseHas('schools', [
            'id' => $school->id,
            'name' => 'Escuela Editada',
            'key' => 'EDIT001'
        ]);
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que se puede editar una escuela con campos opcionales vacíos.
     */
    public function test_assert_that_a_school_can_be_edited_with_optional_fields_empty()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = School::first();
        $community = $this->getTestCommunity();
        
        // Crear escuela con datos completos primero
        $this->actingAs($userAdministrative)->postJson('api/schools/store', [
            'name' => 'Escuela para Editar',
            'key' => 'EDIT001',
            'type_of_school' => 'Primaria',
            'community_id' => $community->id,
            'secondary_number' => 5
        ]);
        
        $school = School::where('name', 'Escuela para Editar')->first();
        
        // Editar solo campos obligatorios
        $response = $this->actingAs($userAdministrative)->putJson('api/schools/' . $school->id, [
            'type_of_school' => 'Primaria',
            'community_id' => $community->id
        ]);
        
        $response->assertStatus(200);
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que no se puede editar una escuela que no existe.
     */
    public function test_assert_that_a_school_cannot_be_edited_that_does_not_exist()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $community = $this->getTestCommunity();
        $nonExistentId = 999999;
        
        $response = $this->actingAs($userAdministrative)->putJson('api/schools/' . $nonExistentId, [
            'name' => 'Escuela Inexistente',
            'type_of_school' => 'Primaria',
            'community_id' => $community->id
        ]);
        
        $response->assertStatus(404);
        
        $this->post('api/logout');
    }

    // ============================================================
    // 4. PRUEBAS DE ELIMINACIÓN
    // ============================================================

    /**
     * Afirmar que se puede eliminar una escuela.
     */
    public function test_assert_that_a_school_can_be_deleted()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $community = $this->getTestCommunity();
        $administrativeHuman = $this->getAdministrativeHuman();
        
        // Crear escuela para eliminar
        $school = School::create([
            'name' => 'Escuela a Eliminar',
            'key' => 'DEL001',
            'type_of_school' => 'Primaria',
            'community_id' => $community->id,
            'secondary_number' => 3,
            'human_id' => $administrativeHuman->id
        ]);
        
        $response = $this->actingAs($userAdministrative)->deleteJson('api/schools/' . $school->id);
        
        $response->assertStatus(200)
            ->assertJson(['message' => '¡Listo! Tu dato fue eliminado bien']);
        
        // Verificar que el campo deleted_at NO sea null (fue eliminado suavemente)
        $this->assertSoftDeleted('schools', [
            'id' => $school->id
        ]);
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que no se puede eliminar una escuela que no existe.
     */
    public function test_assert_that_a_school_cannot_be_deleted_that_does_not_exist()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $nonExistentId = 999999;
        
        $response = $this->actingAs($userAdministrative)->deleteJson('api/schools/' . $nonExistentId);
        
        $response->assertStatus(404);
        
        $this->post('api/logout');
    }

    // ============================================================
    // 5. PRUEBAS DE LISTADO Y PAGINACIÓN
    // ============================================================

    /**
     * Afirmar que al obtener escuelas tiene paginación.
     */
    public function test_assert_that_obtaining_schools_has_pagination()
    {
        $userAdministrative = $this->getAdministrativeUser();
        
        $response = $this->actingAs($userAdministrative)->getJson('api/schools/10/');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'key',
                        'type_of_school',
                        'community_id',
                        'secondary_number',
                        'community' => ['id', 'name'],
                        'name_community'
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
        
        $response = $this->actingAs($userAdministrative)->getJson('api/schools/10/');
        
        $data = $response->json('data');
        
        // Verificar que el primer ID es mayor que el segundo (descendente)
        if (count($data) >= 2) {
            $this->assertGreaterThan($data[1]['id'], $data[0]['id']);
        }
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que las escuelas son paginadas correctamente por 10 registros.
     */
    public function test_assert_that_schools_are_correctly_paginated_by_10_records()
    {
        $userAdministrative = $this->getAdministrativeUser();
        
        $response = $this->actingAs($userAdministrative)->getJson('api/schools/10/');
        
        $this->assertEquals(10, $response->json('per_page'));
        $this->assertCount(10, $response->json('data'));
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que se consultan todas las escuelas al seleccionar todos.
     */
    public function test_assert_that_all_schools_are_consulted_when_selecting_all()
    {
        $userAdministrative = $this->getAdministrativeUser();
        
        // Contar total de escuelas
        $totalSchools = School::count();
        
        $response = $this->actingAs($userAdministrative)->getJson('api/schools/' . $totalSchools . '/');
        
        $data = $response->json('data');
        $this->assertEquals($totalSchools, count($data));
        
        $this->post('api/logout');
    }

    // ============================================================
    // 6. PRUEBAS DE BÚSQUEDA
    // ============================================================

    /**
     * Afirmar que la búsqueda por "name" es buena.
     */
    public function test_assert_that_searching_by_name_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();
        
        // Buscar "Redención" (existe en los seeders)
        $response = $this->actingAs($userAdministrative)->getJson('api/schools/10/Redención');
        
        $response->assertStatus(200);
        $data = $response->json('data');
        
        $this->assertGreaterThan(0, count($data));
        $this->assertEquals('Redención', $data[0]['name']);
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "name" en mayúsculas y minúsculas es buena.
     */
    public function test_assert_that_searching_by_name_with_uppercase_and_lowercase_letters_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();
        
        // Búsqueda en minúsculas
        $responseLower = $this->actingAs($userAdministrative)->getJson('api/schools/10/redención');
        $responseLower->assertStatus(200);
        $this->assertGreaterThan(0, count($responseLower->json('data')));
        
        // Búsqueda en mayúsculas
        $responseUpper = $this->actingAs($userAdministrative)->getJson('api/schools/10/REDENCIÓN');
        $responseUpper->assertStatus(200);
        $this->assertGreaterThan(0, count($responseUpper->json('data')));
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que al buscar una "name" que no existe, no regresa nada.
     */
    public function test_assert_that_searching_by_name_that_does_not_exist_returns_nothing()
    {
        $userAdministrative = $this->getAdministrativeUser();
        
        $response = $this->actingAs($userAdministrative)->getJson('api/schools/10/EscuelaInexistente');
        
        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
        $this->assertEquals(0, $response->json('total'));
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "key" es buena.
     */
    public function test_assert_that_searching_by_key_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();
        
        // Buscar "20DPB0239T" (existe en los seeders)
        $response = $this->actingAs($userAdministrative)->getJson('api/schools/10/20DPB0239T');
        
        $response->assertStatus(200);
        $data = $response->json('data');
        
        $this->assertGreaterThan(0, count($data));
        $this->assertEquals('20DPB0239T', $data[0]['key']);
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "key" en mayúsculas y minúsculas es buena.
     */
    public function test_assert_that_searching_by_key_with_uppercase_and_lowercase_letters_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();
        
        // Búsqueda en minúsculas
        $responseLower = $this->actingAs($userAdministrative)->getJson('api/schools/10/20dpb0239t');
        $responseLower->assertStatus(200);
        $this->assertGreaterThan(0, count($responseLower->json('data')));
        
        // Búsqueda en mayúsculas
        $responseUpper = $this->actingAs($userAdministrative)->getJson('api/schools/10/20DPB0239T');
        $responseUpper->assertStatus(200);
        $this->assertGreaterThan(0, count($responseUpper->json('data')));
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que al buscar por una "key" que no existe, no regresa nada.
     */
    public function test_assert_that_searching_by_key_that_does_not_exist_returns_nothing()
    {
        $userAdministrative = $this->getAdministrativeUser();
        
        $response = $this->actingAs($userAdministrative)->getJson('api/schools/10/KEYINEXISTENTE');
        
        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
        $this->assertEquals(0, $response->json('total'));
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "type_of_school" es buena.
     */
    public function test_assert_that_searching_by_type_of_school_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();
        
        // Buscar "Primaria"
        $response = $this->actingAs($userAdministrative)->getJson('api/schools/10/Primaria');
        
        $response->assertStatus(200);
        $data = $response->json('data');
        
        $this->assertGreaterThan(0, count($data));
        $this->assertEquals('Primaria', $data[0]['type_of_school']);
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "type_of_school" en mayúsculas y minúsculas es buena.
     */
    public function test_assert_that_searching_by_type_of_school_with_uppercase_and_lowercase_letters_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();
        
        // Búsqueda en minúsculas
        $responseLower = $this->actingAs($userAdministrative)->getJson('api/schools/10/primaria');
        $responseLower->assertStatus(200);
        $this->assertGreaterThan(0, count($responseLower->json('data')));
        
        // Búsqueda en mayúsculas
        $responseUpper = $this->actingAs($userAdministrative)->getJson('api/schools/10/PRIMARIA');
        $responseUpper->assertStatus(200);
        $this->assertGreaterThan(0, count($responseUpper->json('data')));
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que al buscar por "type_of_school" que no existe, no regresa nada.
     */
    public function test_assert_that_searching_by_type_of_school_that_does_not_exist_returns_nothing()
    {
        $userAdministrative = $this->getAdministrativeUser();
        
        $response = $this->actingAs($userAdministrative)->getJson('api/schools/10/Secundaria');
        
        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
        $this->assertEquals(0, $response->json('total'));
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "community_id" es buena.
     */
    public function test_assert_that_searching_by_community_id_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $community = Community::where('name', 'San Juan Monte Flor')->first();
        
        $response = $this->actingAs($userAdministrative)->getJson('api/schools/10/' . $community->name);
        
        $response->assertStatus(200);
        $data = $response->json('data');
        
        $this->assertGreaterThan(0, count($data));
        $this->assertEquals($community->id, $data[0]['community_id']);
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "community_id" en mayúsculas y minúsculas es buena.
     */
    public function test_assert_that_searching_by_community_id_with_uppercase_and_lowercase_letters_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $community = Community::where('name', 'San Juan Monte Flor')->first();
        
        // Búsqueda en minúsculas
        $responseLower = $this->actingAs($userAdministrative)->getJson('api/schools/10/' . strtolower($community->name));
        $responseLower->assertStatus(200);
        $this->assertGreaterThan(0, count($responseLower->json('data')));
        
        // Búsqueda en mayúsculas
        $responseUpper = $this->actingAs($userAdministrative)->getJson('api/schools/10/' . strtoupper($community->name));
        $responseUpper->assertStatus(200);
        $this->assertGreaterThan(0, count($responseUpper->json('data')));
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que al buscar por "community_id" que no existe, no regresa nada.
     */
    public function test_assert_that_searching_by_community_id_that_does_not_exist_returns_nothing()
    {
        $userAdministrative = $this->getAdministrativeUser();
        
        $response = $this->actingAs($userAdministrative)->getJson('api/schools/10/ComunidadInexistente');
        
        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
        $this->assertEquals(0, $response->json('total'));
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que la búsqueda por "secondary_number" es buena.
     */
    public function test_assert_that_searching_by_secondary_number_is_good()
    {
        $userAdministrative = $this->getAdministrativeUser();
        
        // Buscar "1" (existe en los seeders)
        $response = $this->actingAs($userAdministrative)->getJson('api/schools/10/1');
        
        $response->assertStatus(200);
        $data = $response->json('data');
        
        $this->assertGreaterThan(0, count($data));
        $this->assertEquals(1, $data[0]['secondary_number']);
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que al buscar por "secondary_number" que no existe, no regresa nada.
     */
    public function test_assert_that_searching_by_secondary_number_that_does_not_exist_returns_nothing()
    {
        $userAdministrative = $this->getAdministrativeUser();
        
        $response = $this->actingAs($userAdministrative)->getJson('api/schools/10/99');
        
        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
        $this->assertEquals(0, $response->json('total'));
        
        $this->post('api/logout');
    }

    // ============================================================
    // 7. PRUEBAS DE RELACIONES
    // ============================================================

    /**
     * Afirmar que al obtener escuela, incluye la relación con comunidad.
     */
    public function test_assert_that_when_obtaining_school_it_includes_the_relationship_with_community()
    {
        $userAdministrative = $this->getAdministrativeUser();
        
        $response = $this->actingAs($userAdministrative)->getJson('api/schools/10/');
        
        $response->assertStatus(200);
        $data = $response->json('data');
        
        if (count($data) > 0) {
            $this->assertArrayHasKey('community', $data[0]);
            $this->assertArrayHasKey('id', $data[0]['community']);
            $this->assertArrayHasKey('name', $data[0]['community']);
            $this->assertArrayHasKey('name_community', $data[0]);
        }
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que al crear escuela con community_id, verificar que la relación se establece.
     */
    public function test_assert_that_when_creating_school_with_community_id_the_relationship_is_established()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $community = $this->getTestCommunity();
        
        $response = $this->actingAs($userAdministrative)->postJson('api/schools/store', [
            'name' => 'Escuela para Relación',
            'type_of_school' => 'Primaria',
            'community_id' => $community->id
        ]);
        
        $response->assertStatus(200);
        
        // Verificar que la relación se estableció correctamente
        $school = School::where('name', 'Escuela para Relación')->first();
        $this->assertEquals($community->id, $school->community_id);
        $this->assertEquals($community->name, $school->community->name);
        $this->assertEquals($community->name, $school->name_community);
        
        $this->post('api/logout');
    }

    // ============================================================
    // 8. PRUEBAS DE ESTRUCTURA DE RESPUESTA
    // ============================================================

    /**
     * Afirmar que la respuesta JSON tiene la estructura esperada.
     */
    public function test_assert_that_the_json_response_has_the_expected_structure()
    {
        $userAdministrative = $this->getAdministrativeUser();
        
        $response = $this->actingAs($userAdministrative)->getJson('api/schools/10/');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'key',
                        'type_of_school',
                        'community_id',
                        'secondary_number',
                        'community' => ['id', 'name'],
                        'name_community'
                    ]
                ]
            ]);
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que los campos se devuelven con los nombres correctos.
     */
    public function test_assert_that_the_fields_are_returned_with_the_correct_names()
    {
        $userAdministrative = $this->getAdministrativeUser();
        
        $response = $this->actingAs($userAdministrative)->getJson('api/schools/10/');
        
        $response->assertStatus(200);
        $data = $response->json('data');
        
        if (count($data) > 0) {
            $school = $data[0];
            
            // Verificar que existen los campos esperados
            $this->assertArrayHasKey('id', $school);
            $this->assertArrayHasKey('name', $school);
            $this->assertArrayHasKey('key', $school);
            $this->assertArrayHasKey('type_of_school', $school);
            $this->assertArrayHasKey('community_id', $school);
            $this->assertArrayHasKey('secondary_number', $school);
            $this->assertArrayHasKey('name_community', $school);
            
            // Verificar que NO existen campos sensibles
            $this->assertArrayNotHasKey('human_id', $school);
            $this->assertArrayNotHasKey('deleted_at', $school);
            $this->assertArrayNotHasKey('created_at', $school);
            $this->assertArrayNotHasKey('updated_at', $school);
        }
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que al crear escuela, la respuesta tenga el mensaje correcto.
     */
    public function test_assert_that_when_creating_school_the_response_has_the_correct_message()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $community = $this->getTestCommunity();
        
        $response = $this->actingAs($userAdministrative)->postJson('api/schools/store', [
            'name' => 'Escuela con Mensaje',
            'type_of_school' => 'Primaria',
            'community_id' => $community->id
        ]);
        
        $response->assertStatus(200)
            ->assertJson([
                'message' => '¡Listo! Tus datos se guardaron bien.'
            ]);
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que al editar escuela, la respuesta tenga el mensaje correcto.
     */
    public function test_assert_that_when_editing_school_the_response_has_the_correct_message()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $school = School::first();
        $community = $this->getTestCommunity();
        
        $response = $this->actingAs($userAdministrative)->putJson('api/schools/' . $school->id, [
            'name' => 'Escuela Editada con Mensaje',
            'type_of_school' => 'Primaria',
            'community_id' => $community->id
        ]);
        
        $response->assertStatus(200)
            ->assertJson([
                'message' => '¡Listo! Tus datos se guardaron bien.'
            ]);
        
        $this->post('api/logout');
    }

    /**
     * Afirmar que al eliminar escuela, la respuesta tenga el mensaje correcto.
     */
    public function test_assert_that_when_deleting_school_the_response_has_the_correct_message()
    {
        $userAdministrative = $this->getAdministrativeUser();
        $community = $this->getTestCommunity();
        $administrativeHuman = $this->getAdministrativeHuman();
        
        // Crear escuela para eliminar
        $school = School::create([
            'name' => 'Escuela para Mensaje Delete',
            'type_of_school' => 'Primaria',
            'community_id' => $community->id,
            'human_id' => $administrativeHuman->id
        ]);
        
        $response = $this->actingAs($userAdministrative)->deleteJson('api/schools/' . $school->id);
        
        $response->assertStatus(200)
            ->assertJson([
                'message' => '¡Listo! Tu dato fue eliminado bien'
            ]);
        
        $this->post('api/logout');
    }
}