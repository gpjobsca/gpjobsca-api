<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobResource;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserJobsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $user = Auth::user();

        // Validate user is requesting own jobs
        if ($id != $user->id) {
            abort(403, 'You can only request your jobs');
        }

        $jobs = Job::where('user_id', $id)->get();
        return JobResource::collection($jobs);
    }
}
