<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * @return void
     */
    public function test_successful_response()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'access_token',
            'user',
        ]);
    }

    /**
     * @return void
     */
    public function test_fail_response()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'secret',
        ]);

        $response->assertStatus(401);

        $response->assertJsonStructure([
            'message',
            'line',
            'errors'
        ]);
    }
}
