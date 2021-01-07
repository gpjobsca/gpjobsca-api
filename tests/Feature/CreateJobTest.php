<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateJobTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * An unauthenticated user can't create a job
     *
     * @return void
     */
    public function test_an_unauthenticated_user_cant_create_a_job()
    {
        $response = $this->postJson('/jobs', [
            'title' => 'Create Job Test Job Title',
            'description' => 'Create Job Test Job Description',
            'apply_link' => 'https://www.createjobtestapplylink.com'
        ]);

        // Assert
        $response->assertStatus(401);
    }

    /**
     * An authenticated user can create a job
     *
     * @return void
     */
    public function test_an_authenticated_user_can_create_a_job()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->postJson('/jobs', [
            'title' => 'Create Job Test Job Title',
            'description' => 'Create Job Test Job Description',
            'apply_link' => 'https://www.createjobtestapplylink.com'
        ]);

        // Assert
        $response->assertStatus(200);
        $this->assertDatabaseHas('jobs', [
            'title' => 'Create Job Test Job Title'
        ]);
        $this->assertDatabaseCount('jobs', 1);
    }
}
