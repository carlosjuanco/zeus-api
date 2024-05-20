<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    
    /**
     * Crear un usuario
     *
     * @return void
     */
    // public function test_new_user()
    // {
    //     $user = User::where('role_id', 1)->first();

    //     $response = $this->actingAs($user)->post('api/users', [
    //         'name' => 'Adriana Santiago Morales', 
    //         'email' => Str::random(2) . "@gmail.com",
    //         'password' => Hash::make('password'),
    //         'role_id' => 1 
    //     ]);

    //     $response->assertStatus(200)
    //     ->assertJson(['message' => 'Usuario creado correctamente']);

    //     $this->post('api/logout');
    // }

    /**
     * Comprobar que el nombre juancho de usuario sea unico
     *
     * @return void
     */
    // public function test_verify_name_user_unique()
    // {
    //     $user = User::where('role_id', 1)->first();

    //     $response = $this->actingAs($user)->post('api/users', [
    //         'name' => 'juan', 
    //         'last_name' => 'rojas', 
    //         'second_last_name' => 'garcia', 
    //         'username' => 'juancho', 
    //         'role_id' => 1 
    //     ]);
    //     $response->assertStatus(302)
    //     ->assertSessionHasErrors(['username' => 'El campo usuario ya ha sido registrado.']);
    //     $this->post('api/logout');
    // }

    // public function test_verify_name_is_required()
    // {
    //     $user = User::where('role_id', 1)->first();

    //     $response = $this->actingAs($user)->post('api/users', [
    //         'last_name' => 'rojas', 
    //         'second_last_name' => 'garcia', 
    //         'username' => Str::random(10), 
    //         'role_id' => 1 
    //     ]);
    //     // printf($response->assertStatus);
    //     $response->assertStatus(302)
    //     ->assertSessionHasErrors(['name' => 'El campo nombre es obligatorio.']);
    //     $this->post('api/logout');
    // }
}
