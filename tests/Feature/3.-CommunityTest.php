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
            'name' => $newCommunity,
            'human_id' => $administrativeUser->id
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
}
