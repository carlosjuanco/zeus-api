<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Human;
use App\Models\Community;

class CommunityTest extends TestCase
{
    /**
     * Afirmar que se puede crear una comunidad
     *
     * @return boolean
     */
    public function test_assert_that_a_community_can_be_created()
    {
        // Consultamos el segundo usuario que tiene el rol Administrativo.
        $userAdministrative = User::where('role_id', 2)->first();

        // Registramos quién guarda el registro
        $administrativeUser = Human::where('paternal_surname', 'administrative')->first();

        $newCommunity = 'Nueva comunidad';

        $response = $this->actingAs($userAdministrative)->post('api/communities/store', [
            'name' => $newCommunity
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => '¡Listo! Tus datos se guardaron bien.']);

        // Verificar que la nueva comunidad fue creado en la base de datos
        $this->assertDatabaseHas('communities', [
            'name' => $newCommunity,
            'human_id' => $administrativeUser->id
        ]);

        $this->post('api/logout');
    }

    /**
     * Afirmar que no se puede crear una comunidad sin autenticación.
     */
    public function test_assert_that_a_community_cannot_be_created_without_authentication()
    {
        // Registramos quién guarda el registro
        $administrativeUser = Human::where('paternal_surname', 'administrative')->first();

        $newCommunity = 'Nueva comunidad';
        
        $response = $this->postJson('api/communities/store', [
            'name' => $newCommunity,
            'human_id' => $administrativeUser->id
        ]);
        
        $response->assertStatus(401); // Unauthorized
    }

    /**
     * Afirmar que no se puede crear una comunidad sin permiso
     */
    public function test_assert_that_a_community_cannot_be_created_without_permission()
    {
        //  Consultar un usuario sin permiso de eliminación, es decir, 
        //  que no tenga el rol "Administrativo"
        $userWithoutPermission = User::where('role_id', '!=', 2)->first();
        
        // Registramos quién guarda el registro
        $administrativeUser = Human::where('paternal_surname', 'administrative')->first();

        $newCommunity = 'Nueva comunidad';
        
        $response = $this->actingAs($userWithoutPermission)->postJson('api/communities/store', [
            'name' => $newCommunity,
            'human_id' => $administrativeUser->id
        ]);
        
        $response->assertStatus(403); // Unauthorized

        //  Cerrar sesión
        $this->post('api/logout');
    }

    /**
     * Afirmar que se puede editar una comunidad
     * 
     * @return boolean
     */
    public function test_assert_that_a_community_can_be_edited()
    {
        //  Consultamos el segundo usuario que tiene el rol Administrativo.
        $userAdministrative = User::where('role_id', 2)->first();

        //  Consultamos la comunidad "San Pedro Cholula" y agregamos
        //  una letra "a" demás, para comprobar que se puede editar
        $communitySanPedroCholula = Community::where('name', 'San Pedro Cholula')->first();

        $updatedName = 'San Pedro Cholula a';

        //  Ejecutar la petición
        $response = $this->actingAs($userAdministrative)
            ->put('api/communities/' . $communitySanPedroCholula->id, [
                'name' => $updatedName
            ]);

        //  Verificar resultados
        $response->assertStatus(200)
            ->assertJson([
                'message' => '¡Listo! Tus datos se guardaron bien.',
            ]);

        //  Verificar en la base de datos
        $this->assertDatabaseHas('communities', [
            'id' => $communitySanPedroCholula->id,
            'name' => $updatedName
        ]);

        //  Cerrar sesión
        $this->post('api/logout');

        /**
         * Parte dos de la prueba
         * 
         * Ya que funciono la prueba ahora el registro dejarlo nuevamente como estaba 
         */

        $updatedName = 'San Pedro Cholula';

        //  Ejecutar la petición
        $response = $this->actingAs($userAdministrative)
            ->put('api/communities/' . $communitySanPedroCholula->id, [
                'name' => $updatedName
            ]);

        //  Verificar resultados
        $response->assertStatus(200)
            ->assertJson([
                'message' => '¡Listo! Tus datos se guardaron bien.',
            ]);
    }

    /**
     * Afirmar que no se puede editar una comunidad sin autenticación.
     */
    public function test_assert_that_a_community_cannot_be_edited_without_authentication()
    {
        //  Consultamos la comunidad "San Pedro Cholula" y agregamos
        //  una letra "a" demás, para comprobar que se puede editar
        $communitySanPedroCholula = Community::where('name', 'San Pedro Cholula')->first();

        $updatedName = 'San Pedro Cholula a';

        //  Se utiliza putJson, para obtener una respuesta ordenada, de esta manera
        //  se puede recuperar facilmente el estado de resultado.
        //  Cuando sabemos que nos regresará un resultados correcto ya viene formateado

        //  Ejecutar la petición
        $response = $this->putJson('api/communities/' . $communitySanPedroCholula->id, [
                'name' => $updatedName
            ]);

        //  Verificar resultados
        $response->assertStatus(401); // Unauthorized        
    }

    /**
     * Afirmar que no se puede editar una comunidad sin permiso
     */
    public function test_assert_that_a_community_cannot_be_edited_without_permission()
    {
        //  Consultar un usuario sin permiso de eliminación, es decir, 
        //  que no tenga el rol "Administrativo"
        $userWithoutPermission = User::where('role_id', '!=', 2)->first();

        //  Consultamos la comunidad "San Pedro Cholula" y agregamos
        //  una letra "a" demás, para comprobar que se puede editar
        $communitySanPedroCholula = Community::where('name', 'San Pedro Cholula')->first();

        $updatedName = 'San Pedro Cholula a';
        
        //  Ejecutar la petición
        $response = $this->actingAs($userWithoutPermission)
            ->put('api/communities/' . $communitySanPedroCholula->id, [
                'name' => $updatedName
            ]);

        //  Verificar resultados
        $response->assertStatus(403); // Unauthorized

        //  Cerrar sesión
        $this->post('api/logout');
    }

    /**
     * Afirmar que se puede eliminar una comunidad
     * 
     * @return boolean
     */
    public function test_assert_that_a_community_can_be_deleted()
    {
        //  Consultamos el segundo usuario que tiene el rol Administrativo.
        $userAdministrative = User::where('role_id', 2)->first();
        $this->assertNotNull($userAdministrative, 'No se encontró usuario Administrativo');

        //  Consultamos la comunidad "Nueva comunidad"
        $selectNewCommunity = Community::where('name', 'Nueva comunidad')->first();
        $this->assertNotNull($selectNewCommunity, 'No se encontró la comunidad');

        //  Ejecutar la petición
        $response = $this->actingAs($userAdministrative)
            ->deleteJson('api/communities/' . $selectNewCommunity->id);

        //  Verificar resultados
        $response->assertStatus(200)
            ->assertJson([
                'message' => '¡Listo! Tu dato fue eliminado bien',
            ]);

        //  Verificar que el campo deleted_at NO sea null (fue eliminado suavemente)
        $this->assertSoftDeleted('communities', [
            'id' => $selectNewCommunity->id,
        ]);

        //  Cerrar sesión
        $this->post('api/logout');
    }

    /**
     * Afirmar que no se puede eliminar una comunidad sin autenticación
     */
    public function test_assert_that_a_community_cannot_be_deleted_without_authentication()
    {
        $community = Community::where('name', 'Rio Cacho')->first();
        
        $response = $this->deleteJson('api/communities/' . $community->id);
        
        $response->assertStatus(401); // Unauthorized
        
        // Verificar que no fue eliminada
        $this->assertDatabaseHas('communities', [
            'id' => $community->id,
            'deleted_at' => null
        ]);
    }

    /**
     * Afirmar que no se puede eliminar una comunidad sin permiso
     */
    public function test_assert_that_a_community_cannot_be_deleted_without_permission()
    {
        //  Consultar un usuario sin permiso de eliminación, es decir, 
        //  que no tenga el rol "Administrativo"
        $userWithoutPermission = User::where('role_id', '!=', 2)->first();
        $community = Community::where('name', 'Rio Cacho')->first();
        
        $response = $this->actingAs($userWithoutPermission)
            ->deleteJson('api/communities/' . $community->id);
        
        $response->assertStatus(403); // Forbidden - por el middleware 'can:delete,community'
        
        // Verificar que no fue eliminada
        $this->assertDatabaseHas('communities', [
            'id' => $community->id,
            'deleted_at' => null
        ]);

        //  Cerrar sesión
        $this->post('api/logout');
    }

    /**
     * Afirmar que no se puede eliminar una comunidad que no existe
     */
    public function test_assert_that_you_cannot_eliminate_a_community_that_does_not_exist()
    {
        //  Consultamos el segundo usuario que tiene el rol Administrativo.
        $userAdministrative = User::where('role_id', 2)->first();
        $nonExistentId = 999999;
        
        $response = $this->actingAs($userAdministrative)
            ->deleteJson('api/communities/' . $nonExistentId);

        //  El error 404 hace referencia que no encontró el registro.
        $response->assertStatus(404);
    }

    /**
     * Afirmar que el campo "nombre" es obligatorio.
     *
     * @return boolean
     */
    public function test_assert_that_the_name_field_is_mandatory()
    {
        // Consultamos el segundo usuario que tiene el rol Administrativo.
        $userAdministrative = User::where('role_id', 2)->first();

        // Registramos quién guarda el registro
        $administrativeUser = Human::where('paternal_surname', 'administrative')->first();

        $response = $this->actingAs($userAdministrative)->post('api/communities/store', [
            'human_id' => $administrativeUser->id
        ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['name' => 'El campo nombre es obligatorio.']);

        $this->post('api/logout');
    }

    /**
     * Afirmar que el campo "nombre", no acepte mas de 15 caracteres.
     *
     * @return boolean
     */
    public function test_assert_that_the_name_field_does_not_accept_more_than_15_characters()
    {
        // Consultamos el segundo usuario que tiene el rol Administrativo.
        $userAdministrative = User::where('role_id', 2)->first();

        $newCommunity = 'Nueva comunidad 12345678910';

        $response = $this->actingAs($userAdministrative)->post('api/communities/store', [
            'name' => $newCommunity
        ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['name' => 'El campo nombre no debe ser mayor que 25 caracteres.']);

        $this->post('api/logout');
    }

    /**
     * Afirma que devuelve todas las comunidades para el elemento select.
     *
     * @return boolean
     */
    public function test_assert_that_it_returns_all_communities_for_the_select_element()
    {
        // Consultamos el segundo usuario que tiene el rol Administrativo.
        $userAdministrative = User::where('role_id', 2)->first();
        
        //  Llamar al endpoint
        $response = $this->actingAs($userAdministrative)->getJson('api/communities');
        
        // Assert: Verificar respuesta
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [ // Cada elemento debe tener:
                    'id',
                    'name'
                ]
            ]);

        //  Confirmar que son mas de 12 comunidades
        // O más elegante:
        $this->assertGreaterThan(12, count($response->json()), 'Debe haber más de 12 comunidades');
    }

    /**
     * Afirmar que al obtener comunidades tiene paginación.
     *
     * @return void
     */
    public function test_assert_that_obtaining_communities_has_pagination()
    {
        // Consultamos el segundo usuario que tiene el rol Administrativo.
        $userAdministrative = User::where('role_id', 2)->first();

        //  Solicitar primeras 10 comunidades
        $response = $this->actingAs($userAdministrative)
            ->getJson('api/communities/10/');
        
        //  Verificar respuesta
        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'current_page',
                    'data' => [
                        '*' => ['id', 'name']
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
        
        //  Verificar que solo tiene 10 registros
        $this->assertCount(10, $response->json('data'));
        
        //  Verificar que los campos son solo id y name
        $firstCommunity = $response->json('data')[0];
        $this->assertArrayHasKey('id', $firstCommunity);
        $this->assertArrayHasKey('name', $firstCommunity);
        $this->assertArrayNotHasKey('human_id', $firstCommunity);
        $this->assertArrayNotHasKey('deleted_at', $firstCommunity);
    }

    /**
     * Afirmar ordenamiento descendente por ID
     *
     * @return void
     */
    public function test_assert_descending_order_by_id()
    {        
        //  Consultamos el segundo usuario que tiene el rol Administrativo.
        $userAdministrative = User::where('role_id', 2)->first();

        //  Solicitar primeras 10 comunidades
        $response = $this->actingAs($userAdministrative)
            ->getJson('api/communities/10/');

        //  Consultamos los último 3 registros de comunidades
        $dataCommunities = Community::select('id')->orderBy('id', 'desc')
            ->get()->take(3);
        
        //  Verificar orden descendente (de mayor a menor ID)
        $data = $response->json('data');
        $this->assertEquals($dataCommunities[0]->id, $data[0]['id']);
        $this->assertEquals($dataCommunities[1]->id, $data[1]['id']);
        $this->assertEquals($dataCommunities[2]->id, $data[2]['id']);
    }

    /**
     * Afirmar que las comunidades son paginados correctamente por 10 registros.
     *
     * @return void
     */
    public function test_assert_that_communities_are_correctly_paginated_by_10_records()
    {        
        //  Consultamos el segundo usuario que tiene el rol Administrativo.
        $userAdministrative = User::where('role_id', 2)->first();

        //  Solicitar primeras 10 comunidades
        $response = $this->actingAs($userAdministrative)
            ->getJson('api/communities/10/');
        
        $data = $response->json('data');
        $this->assertEquals(10, count($data));
    }

    /**
     * Afirmar que se consultan todas las comunidades al seleccionar todos.
     *
     * @return void
     */
    public function test_assert_that_all_communities_are_consulted_when_selecting_all()
    {        
        //  Consultamos el segundo usuario que tiene el rol Administrativo.
        $userAdministrative = User::where('role_id', 2)->first();

        //  Consultamos cuantos registros existen
        $totalCommunities = Community::select('id')->count();

        //  Solicitar todas las comunidades
        $response = $this->actingAs($userAdministrative)
            ->getJson('api/communities/' . $totalCommunities. '/');
        
        $data = $response->json('data');
        $this->assertEquals($totalCommunities, count($data));
    }

    /**
     * Afirmar que la búsqueda por nombre es buena
     *
     * @return void
     */
    public function test_assert_that_searching_by_name_is_good()
    {
        //  Consultamos el segundo usuario que tiene el rol Administrativo.
        $userAdministrative = User::where('role_id', 2)->first();

        //  Buscar comunidades que contengan "Cholula"
        $response = $this->actingAs($userAdministrative)
            ->getJson('api/communities/10/Cholula');
        
        //  Afirmar
        $response->assertStatus(200);
        $data = $response->json('data');
        
        $this->assertEquals('San Pedro Cholula', $data[0]['name']);
    }

     /**
     * Afirmar que la búsqueda por nombre con mayusculas y minusculas es buena
     *
     * @return void
     */
    public function test_assert_that_searching_by_name_with_uppercase_and_lowercase_letters_is_good()
    {
        //  Consultamos el segundo usuario que tiene el rol Administrativo.
        $userAdministrative = User::where('role_id', 2)->first();

        //  Buscar en minúsculas
        $responseLower = $this->actingAs($userAdministrative)
            ->getJson('api/communities/10/cholula');
        
        //  Afirmar
        $responseLower->assertStatus(200);
        $this->assertCount(1, $responseLower->json('data'));
        
        //  Buscar en mayúsculas
        $responseUpper = $this->actingAs($userAdministrative)
            ->getJson('api/communities/10/CHOLULA');
        
        //  Afirmar
        $responseUpper->assertStatus(200);
        $this->assertCount(1, $responseUpper->json('data'));
    }

    /**
     * Afirmar que al buscar una comunidad que no existe, no regresa nada
     *
     * @return void
     */
    public function test_assert_that_by_searching_for_a_community_that_does_not_exist_nothing_is_returned()
    {
        //  Consultamos el segundo usuario que tiene el rol Administrativo.
        $userAdministrative = User::where('role_id', 2)->first();

        //  Buscar una comunidad que no existe
        $response = $this->actingAs($userAdministrative)
            ->getJson('api/communities/10/NoExistente');
        
        //  Afirmar
        $response->assertStatus(200);

        $this->assertCount(0, $response->json('data'));
        $this->assertEquals(0, $response->json('total'));
    }
}
