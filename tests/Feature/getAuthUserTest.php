<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetAuthUserTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Unauthorized user cannot access the /user route
     *
     * @return void
     */
    public function test_unauthorized_user_cannot_access_the_user_route()
    {
        $response = $this->getJson('/user');

        $response->assertStatus(401);
    }

    /**
     * Authorized user can access their own user
     *
     * @return void
     */
    public function test_authorized_user_can_access_the_user_route()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/user');

        $response->assertStatus(200);
        $response->assertJson(['data' => ['name' => $user->name, 'email' => $user->email]]);
    }
}
