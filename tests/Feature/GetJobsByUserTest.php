<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetJobsByUserTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Unauthenticated user cannot request user's jobs
     *
     * @return void
     */
    public function test_unauthenticated_user_cannot_request_users_jobs()
    {
        $response = $this->getJson('/user/1/jobs');
        $response->assertStatus(401);
    }

    /**
     * User can request own jobs
     *
     * @return void
     */
    public function test_user_can_get_own_jobs()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        Job::factory()->count(3)->for($user)->create();
        Job::factory()->count(7)->for($user2)->create();
        $this->actingAs($user);

        $response = $this->getJson("/user/$user->id/jobs");

        $response->assertJsonCount(3, 'data');
        $response->assertStatus(200);
    }

    /**
     * User cannot request another user's jobs
     *
     * @return void
     */
    public function test_user_cannot_get_another_users_jobs()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        Job::factory()->count(1)->for($user)->create();
        $this->actingAs($user2);

        $response = $this->getJson("/user/$user->id/jobs");

        $response->assertStatus(403);
    }
}
