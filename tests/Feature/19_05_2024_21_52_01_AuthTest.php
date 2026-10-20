<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * Comprobar que el usuario este logueado.
     *
     * @return void
    */
    public function test_verify_that_the_user_is_logged_in()
    {
        $response = $this->post('api/check');
        $response->assertStatus(405);
    }

    /**
     * Comprobar que la ruta de inicio de sesión no requiera un usuario logueado.
     *
     * @return void
    */
    public function test_validate_that_the_login_route_does_not_require_login()
    {
        $response = $this->post('api/login',[
            'email' => 'carlosjuancho328@gmail.com', 
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $this->post('api/logout');
    }

    /**
     * Comprobar que la contraseña es incorrecta.
     *
     * @return void
    */
    public function test_check_that_the_password_is_incorrect()
    {
        $response = $this->post('api/login',[
            'email' => 'carlosjuancho328@gmail.com', 
            'password' => '123444',
        ]);

        $response->assertStatus(422)
        ->assertJson(['errors' => ['password' => ['Contraseña incorrecta'] ]]);

        $this->post('api/logout');
    }

    /**
     * Comprobar que la estructura del correo sea valido.
     *
     * @return void
     */
    public function test_check_that_the_email_structure_is_valid()
    {
        $response = $this->post('api/login',[
            'email' => 'carlosjuancho328',
            'password' => '123444',
        ]);

        // dd($response);

        $response->assertStatus(302)
        ->assertSessionHasErrors(['email' => 'La estructura del correo no es válido.']);

        $this->post('api/logout');
    }
}
