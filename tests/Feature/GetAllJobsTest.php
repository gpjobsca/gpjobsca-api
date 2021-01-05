<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JobTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * All jobs in the database are returned upon a GET request to the /jobs path
     *
     * @return void
     */
    public function test_assert_all_jobs_can_be_retrieved()
    {
        $user = User::factory()->create();

        // 3 jobs created
        Job::factory()
            ->count(3)
            ->for($user)
            ->create();
        $response = $this->get('/jobs');

        // 3 jobs retrieved
        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }
}
