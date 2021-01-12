<?php

namespace Tests\Feature;

use App\Models\Job;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DeleteJobTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A job can't be deleted by an unauthenticated user
     *
     * @return void
     */
    public function test_a_job_cant_be_deleted_by_an_unauthenticated_user()
    {
        $response = $this->deleteJson('/jobs/1');
        $response->assertStatus(401);
    }

    /**
     * Test a job can be deleted by authenticated user
     *
     * @return void
     */
    public function test_authenticated_user_can_delete_job()
    {
        $user = User::factory()->create();
        $job = Job::factory()
            ->for($user)
            ->create();

        $this->actingAs($user);

        $response = $this->deleteJson("/jobs/$job->id");

        $response->assertStatus(200);
        $this->assertSoftDeleted($job);
    }

    /**
     * Test a job can't be deleted by a different authenticated user
     *
     * @return void
     */
    public function test_authenticated_user_cant_delete_job_of_other_user()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $job = Job::factory()
            ->for($user)
            ->create();

        Sanctum::actingAs($user2);

        $response = $this->deleteJson('/jobs/1');

        $response->assertStatus(403);
        $this->assertDatabaseHas('jobs', ['id' => $job->id]);
    }
}
