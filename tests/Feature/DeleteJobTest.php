<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;

class DeleteJobTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test a job can be deleted.
     *
     * @return void
     */
    public function testDeleteJobTest()
    {
        $user = User::factory()->create();
        $job = Job::factory()
            ->for($user)
            ->create();

        $this->actingAs($user);

        $response = $this->deleteJson('/jobs/1');

        $response->assertStatus(200);
        $this->assertSoftDeleted($job);
    }
}
