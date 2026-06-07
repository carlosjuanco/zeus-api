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
    }
}
