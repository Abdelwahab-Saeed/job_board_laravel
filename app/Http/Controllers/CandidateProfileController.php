<?php

namespace App\Http\Controllers;

use App\Models\CandidateProfile;
use Illuminate\Http\Request;

class CandidateProfileController extends Controller
{
    public function index()
    {
        return CandidateProfile::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'location' => 'required|string|max:255',
            'linkedin_profile' => 'nullable|url|max:255',
            'title' => 'required|string|max:255',
            'profile_photo' => 'nullable|url|max:255',
            'experience_level' => 'required|string|max:50',
            'skills' => 'required|string',
            'experience' => 'nullable|string',
            'education' => 'nullable|string',
        ]);

        CandidateProfile::create($validated);

        return response()->json(['message' => 'Candidate profile added successfully'], 201);
    }

    public function show($id)
    {
        return CandidateProfile::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $candidate = CandidateProfile::findOrFail($id);

        $validated = $request->validate([
            'name' => 'string|max:100',
            'email' => "email|unique:candidate_profiles,email,{$id}",
            'phone' => 'nullable|string',
            'location' => 'nullable|string',
            'linkedin_profile' => 'nullable|url',
            'experience_level' => 'nullable|in:junior,mid,senior',
            'title' => 'nullable|string|max:100',
            'profile_photo' => 'nullable|url',
            'experience' => 'nullable|string',
            'skills' => 'nullable|string',
            'education' => 'nullable|string',
        ]);

        $candidate->update($validated);
        return response()->json($candidate);
    }

    public function destroy($id)
    {
        $candidate = CandidateProfile::findOrFail($id);
        $candidate->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
