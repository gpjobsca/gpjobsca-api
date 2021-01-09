<?php

namespace Tests\Feature;

use App\Models\Job;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EditJobTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * An authenticated user cannot edit a job
     *
     * @return void
     */
    public function test_unauthenticated_user_cannot_edit_job()
    {
        $user = User::factory()->create();
        $job = Job::factory()->for($user)->create();

        $response = $this->putJson("/jobs/$job->id", ["title" => "New Job Title"]);

        $response->assertStatus(401);
    }

    /**
     * An authenticated user cannot edit a job owned by another user
     *
     * @return void
     */
    public function test_authenticated_user_cannot_edit_job_of_anothor_user()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $job2 = Job::factory()->for($user2)->create();
        $this->actingAs($user);

        $response = $this->putJson("/jobs/$job2->id", $job2->toArray());

        $response->assertStatus(403);
    }

    /**
     * An authenticated user cann edit their own job
     *
     * @return void
     */
    public function test_authenticated_user_can_edit_their_own_job()
    {
        $user = User::factory()->create();
        $job = Job::factory()->for($user)->create();
        Sanctum::actingAs($user);

        $response = $this->putJson(
            "/jobs/$job->id",
            array_merge(
                ["title" => "New Title"],
                $job->toArray()
            )
        );

        $response->assertStatus(200);
    }
}
