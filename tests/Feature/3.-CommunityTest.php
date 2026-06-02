<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Human;

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
}
