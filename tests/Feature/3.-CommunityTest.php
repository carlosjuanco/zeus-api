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
}
