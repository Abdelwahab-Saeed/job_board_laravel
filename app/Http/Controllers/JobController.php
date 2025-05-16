<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;


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

    public function filter(Request $request)
    {
        $query = Job::query();
    
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->keyword . '%')
                  ->orWhere('description', 'like', '%' . $request->keyword . '%');
            });
        }
    
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }
    
        if ($request->filled('experience')) {
            $query->where('experience_level', $request->experience);
        }
    
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
    
        if ($request->filled('salary_min')) {
            $query->where('salary', '>=', $request->salary_min);
        }
        
        if ($request->filled('salary_max')) {
            $query->where('salary', '<=', $request->salary_max);
        }
        
        if ($request->filled('posted_within_days')) {
            $date = \Carbon\Carbon::now()->subDays($request->posted_within_days);
            $query->where('created_at', '>=', $date);
        }
    
        $perPage = $request->input('per_page', 10);
    
        $jobs = $query->latest()->paginate($perPage);
    
        return response()->json([
            'count' => $jobs->total(),
            'jobs' => $jobs->items(),
            'current_page' => $jobs->currentPage(),
            'last_page' => $jobs->lastPage(),
            'per_page' => $jobs->perPage(),
            'total' => $jobs->total(),
        ]);
    }
    
    
}
