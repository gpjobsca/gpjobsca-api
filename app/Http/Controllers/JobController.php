<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return $this->response($jobs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $job = new Job;
        $job->title = $request->title;
        $job->description = $request->description;
        $job->apply_link = $request->apply_link;
        $job->expired_at = now()->addDays(30);
        $job->user()->associate(Auth::id());
        $job->save();
        return $this->response($job);
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
        return $this->response($job);
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

        $this->confirmOwnership($job);

        $job->update([
            'title' => $request->title,
            'description' => $request->description,
            'apply_link' => $request->apply_link
        ]);
        return $this->response($job);
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

        $this->confirmOwnership($job);

        $job->delete();
        return response('', 200);
    }

    /**
     * Confirm the passed in job is owned by the authenticated user
     *
     * @param \App\Models\Job
     * @return void
     */
    private function confirmOwnership(Job $job)
    {
        if (Auth::id() !== $job->user_id) {
            abort(403, 'Cannot alter another user\'s job.');
        }
    }
}
