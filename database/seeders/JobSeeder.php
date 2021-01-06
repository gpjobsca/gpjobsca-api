<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $testUser = new User;
        $testUser->name = 'Foo Bar';
        $testUser->email = 'foo@bar.com';
        $testUser->password = Hash::make('password');
        $testUser->company_name = 'XYZ Corp.';
        $testUser->company_location = 'Grande Prairie';
        $testUser->save();
        $testJob = Job::factory()->create();
        $testJob->user($testUser);
        $testJob->save();


        User::factory()
            ->times(10)
            ->hasJobs(rand(1, 3))
            ->create();
    }
}
