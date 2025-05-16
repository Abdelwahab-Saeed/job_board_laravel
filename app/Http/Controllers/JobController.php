<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Models\Job;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $jobs = Job::with('comments')->get();

        if($jobs->count() == 0){
            return response()->json(
                [
                    'message' => "No Jobs at the time!"
                ]
            );
        }

        return response()->json([
            "success" => true,
            "data" => $jobs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobRequest $request)
    {
        //
        $job = Job::create($request->all());

        return response()->json([
            "success" => true,
            'message' => 'Job created successfully',
            'data' => $job,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $job = Job::find($id);

        if(!$job) {
            return response()->json([
                "success" => false,
                'message' => 'Job not found',
            ], 404);
        }

        return response()->json([
            "success" => true,
            'data' => $job
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobRequest $request, string $id)
    {
        //
        $job = Job::find($id);

        if(!$job) {
            return response()->json([
                "success" => false,
                'message' => 'Job not found',
            ], 404);
        }

        $job->update($request->all());

        return response()->json([
            "success" => true,
            'message' => 'Job updated successfully',
            'data' => $job,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $job = Job::find($id);

        if(!$job) {
            return response()->json([
                "success" => false,
                'message' => 'Job not found',
            ], 404);
        }

        $job->delete();

        return response()->json([
            "success" => true,
            'message' => 'Job deleted successfully',
        ]);
    }
    public function analytics($employerId)
    {
      
       
        $jobs = Job::where('employer_id', $employerId)->withCount('applications')->get();
    
        $totalJobs = $jobs->count();
        $totalApplications = $jobs->sum('applications_count');
        $averageApplications = $totalJobs > 0 ? round($totalApplications / $totalJobs, 2) : 0;
    
        $jobsSorted = $jobs->sortByDesc('applications_count')->values();
    
       
        $jobsData = $jobsSorted->map(function ($job) {
            return [
                'id' => $job->id,
                'title' => $job->title,
                'applications_count' => $job->applications_count,
            ];
        });
    
        return response()->json([
            'total_jobs' => $totalJobs,
            'total_applications' => $totalApplications,
            'average_applications_per_job' => $averageApplications,
            'jobs' => $jobsData,
        ]);
    }
    
}
