<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

use App\Models\User;

class UserTest extends TestCase
{
    /**
     * Crear un usuario
     *
     * @return void
     */
    public function test_new_user()
    {
        $user = User::where('role_id', 1)->first();

        $email = Str::random(2) . "@gmail.com";
        $response = $this->actingAs($user)->post('api/users', [
            'name' => 'Adriana Santiago Morales', 
            'email' => $email,
            'password' => Str::random(10),
            'role_id' => 1
        ]);

        $response->assertStatus(200)
        ->assertJson(['message' => 'Usuario creado correctamente']);

        // Verificar que el usuario fue creado en la base de datos
        $this->assertDatabaseHas('users', [
            'email' => $email,
            'role_id' => 1
        ]);

        $this->post('api/logout');
    }

    /**
     * Comprobar que el campo email sea único
     *
     * @return void
     */
    public function test_verify_the_field_email_unique()
    {
        $user = User::where('role_id', 1)->first();

        $response = $this->actingAs($user)->post('api/users', [
            'name' => 'juan', 
            'email' => 'carlosjuancho328@gmail.com',
            'password' => Str::random(10),
            'role_id' => 1 
        ]);

        $response->assertStatus(302)
        ->assertSessionHasErrors(['email' => 'El correo ya ha sido registrado.']);

        $this->post('api/logout');
    }

    /**
     * Comprobar que el campo nombre sea requerido
     *
     * @return void
     */
    public function test_verify_name_is_required()
    {
        $user = User::where('role_id', 1)->first();

        $response = $this->actingAs($user)->post('api/users', [
            'email' => Str::random(2) . "@gmail.com",
            'password' => Str::random(10), 
            'role_id' => 1 
        ]);

        $response->assertStatus(302)
        ->assertSessionHasErrors(['name' => 'El campo nombre es obligatorio.']);

        $this->post('api/logout');
    }

    /**
     * Comprobar que el campo email sea requerido
     *
     * @return void
     */
    public function test_verify_email_is_required()
    {
        $user = User::where('role_id', 1)->first();

        $response = $this->actingAs($user)->post('api/users', [
            'name' => 'juan',
            'password' => Str::random(10), 
            'role_id' => 1 
        ]);

        $response->assertStatus(302)
        ->assertSessionHasErrors(['email' => 'El campo correo es obligatorio.']);

        $this->post('api/logout');
    }

    /**
     * Comprobar que el campo role_id sea requerido
     *
     * @return void
     */
    public function test_verify_role_id_is_required()
    {
        $user = User::where('role_id', 1)->first();

        $response = $this->actingAs($user)->post('api/users', [
            'name' => 'juan',
            'email' => Str::random(2) . "@gmail.com",
            'password' => Str::random(10), 
        ]);

        $response->assertStatus(302)
        ->assertSessionHasErrors(['role_id' => 'El campo role id es obligatorio.']);

        $this->post('api/logout');
    }

    /**
     * Comprobar que el campo role_id exista en la tabla roles
     *
     * @return void
     */
    public function test_check_that_the_role_id_field_exists_in_the_roles_table()
    {
        $user = User::where('role_id', 1)->first();

        $response = $this->actingAs($user)->post('api/users', [
            'name' => 'garcia',
            'email' => Str::random(2) . "@gmail.com",
            'password' => Str::random(10),
            'role_id' => 1000
        ]);

        $response->assertStatus(302)
        ->assertSessionHasErrors(['role_id' => 'role id no existe.']);

        $this->post('api/logout');
    }
}
