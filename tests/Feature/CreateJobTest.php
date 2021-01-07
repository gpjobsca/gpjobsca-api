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
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateJob()
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
