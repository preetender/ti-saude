<?php

namespace Tests\Feature;

use App\Domain\Users\Models\User;

use Tests\TestCase;

class AccountTest extends TestCase
{
    /**
     * @return void
     */
    public function test_successful_response()
    {
        $user = User::first();

        $response = $this
            ->assertAuthenticatedAs($user)
            ->get('/api/v1/account');

        $response
            ->assertStatus(200)
            ->assertJson($user->toArray());
    }
}
