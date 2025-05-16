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

        $application = Application::create([
            'candidate_id' => $user->id,
            'job_id' => $jobId,
            'resume_snapshot' => $request->resume_snapshot,
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
}






