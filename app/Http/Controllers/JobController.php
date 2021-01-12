<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobResource;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs = Job::where('expired_at', '>', now())->with('user')->get();
        return JobResource::collection($jobs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->jobValidationRules());

        $job = new Job;
        $job->title = $request->title;
        $job->description = $request->description;
        $job->apply_link = $request->apply_link;
        $job->expired_at = now()->addDays(30);
        $job->user()->associate(Auth::id());
        $job->save();
        return new JobResource($job);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $job = Job::with('user')->findOrFail($id);
        return new JobResource($job);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $job = Job::findOrFail($id);
        $this->confirmOwnership(Auth::user(), $job);
        $request->validate($this->jobValidationRules());

        $job->update([
            'title' => $request->title,
            'description' => $request->description,
            'apply_link' => $request->apply_link
        ]);
        return new JobResource($job);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $job = Job::findOrFail($id);

        $this->confirmOwnership(Auth::user(), $job);

        $job->delete();
        return response('', 200);
    }

    /**
     * Confirm the passed in job is owned by the authenticated user
     *
     * @param \App\Models\User
     * @param \App\Models\Job
     * @return void
     */
    private function confirmOwnership(User $user, Job $job)
    {
        if ($user->id != $job->user_id) {
            abort(403, 'Cannot alter another user\'s job.');
        }
    }

    /**
    * Get the validation rules used to validate jobs.
    *
    * @return array
    */
    private function jobValidationRules()
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'apply_link' => ['required', 'url']
        ];
    }
}
