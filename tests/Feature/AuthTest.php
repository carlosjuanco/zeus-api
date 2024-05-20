<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_verify_that_the_user_is_logged_in()
    {
        $response = $this->post('api/check');
        $response->assertStatus(405);
    }

    public function test_validate_that_the_login_route_does_not_require_login()
    {
        $response = $this->post('api/login',[
            'email' => 'carlosjuancho328@gmail.com', 
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $this->post('api/logout');
    }
}
