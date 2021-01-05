<?php

namespace Tests\Feature;

use App\Models\Job;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;

class GetOneJobTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * Can get one job
     *
     * @return void
     */
    public function testExample()
    {
        // Arrange
        $user = User::factory()->create();
        $job = Job::factory()->for($user)->create();

        dd($job->id, $user->id);

        // Act
        $response = $this->get('/jobs/1');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonPath('data.id', $job->id);
    }
}
