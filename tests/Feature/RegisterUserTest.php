<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use DatabaseMigrations, WithFaker;
    /**
     * A new user can be registered
     *
     * @return void
     */
    public function test_a_new_user_can_be_registered()
    {
        $response = $this->postJson('/register', [
            'name' => 'Colby Garland',
            'email' => 'colby.foo@bar.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'company_name' => $this->faker->company,
            'company_location' => 'Grande Prairie'
        ]);

        $response->assertStatus(201) ;
        $this->assertDatabaseHas('users', ['email' => 'colby.foo@bar.com']);
    }
}
