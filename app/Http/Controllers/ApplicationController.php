<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{


    public function apply(StoreApplicationRequest $request, $jobId)
    {
        $user = auth()->user();
    
        $existing = Application::where('candidate_id', $user->id)
            ->where('job_id', $jobId)
            ->first();
    
        if ($existing) {
            return response()->json(['message' => 'You already applied to this job.'], 409);
        }
    
     
        if (!$request->hasFile('resume_snapshot')) {
            return response()->json(['message' => 'Resume file is required.'], 422);
        }
    
        
        $resumeName = $user->name . '_' . time() . '.' . $request->file('resume_snapshot')->getClientOriginalExtension();
$resumePath = $request->file('resume_snapshot')->storeAs('resumes', $resumeName, 'public');

    
     
        $application = Application::create([
            'candidate_id' => $user->id,
            'job_id' => $jobId,
            'resume_snapshot' => $resumePath,  
            'cover_letter' => $request->cover_letter,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'status' => 'pending',
        ]);
    
        return response()->json([
            'message' => 'Application submitted successfully.',
            'data' => $application
        ], 201);
    }
    

    public function cancel($id)
    {
        $application = Application::find($id);  

        if (!$application || $application->candidate_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized or application not found.'], 403);
        }

        $application->delete();  

        return response()->json(['message' => 'Application canceled successfully.']);
    }

    public function myApplications()
    {
        $applications = auth()->user()->applications()->with('job')->get();
    
        return response()->json([
            'data' => $applications
        ]);
    }
    
    public function updateStatus(UpdateApplicationRequest $request, $id)
    {
        $application = Application::with('job')->find($id);

        if (!$application) {
            return response()->json(['message' => 'Application not found'], 404);
        }

        if ($application->job->employer_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $application->status = $request->status;
        $application->save();

        return response()->json([
            'message' => 'Application status updated.',
            'data' => $application
        ]);
    }

    public function getApplication($id)
    {
        $application = Application::with('job')->find($id);

        if (!$application) {
            return response()->json(['message' => 'Application not found'], 404);
        }

        if ($application->job->employer_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return response()->json([
            'data' => $application
        ]);
    }
    public function getApplicationsByJob($jobId)
    {
        $applications = Application::where('job_id', $jobId)->with('candidate')->get();

        if ($applications->isEmpty()) {
            return response()->json(['message' => 'No applications found for this job.'], 404);
        }

        return response()->json([
            'data' => $applications
        ]);
    }
    // public function getApplicationsByEmployer($employerId)
    // {
    //     $applications = Application::whereHas('job', function ($query) use ($employerId) {
    //         $query->where('employer_id', $employerId);
    //     })->with('user')->get();

    //     if ($applications->isEmpty()) {
    //         return response()->json(['message' => 'No applications found for this employer.'], 404);
    //     }

    //     return response()->json([
    //         'data' => $applications
    //     ]);
    // } 
    
    public function getApplicationsByEmployer($employerId)
    {
        $perPage = request()->get('per_page', 10);

        $applications = Application::whereIn('status', ['pending'])
            ->whereHas('job', function ($query) use ($employerId) {
                $query->where('employer_id', $employerId)
                    ->where('status', '!=', 'closed'); // Add this condition
            })
            ->with('user', 'job')
            ->paginate($perPage);

        if ($applications->isEmpty()) {
            return response()->json(['message' => 'No applications found for this employer.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $applications->items(),
            'current_page' => $applications->currentPage(),
            'last_page' => $applications->lastPage(),
            'per_page' => $applications->perPage(),
            'total_applications' => $applications->total(),
            'total_pages' => $applications->lastPage(),
        ]);
    }

}






