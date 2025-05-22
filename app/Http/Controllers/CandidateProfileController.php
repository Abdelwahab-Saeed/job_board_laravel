<?php
namespace App\Http\Controllers;

use App\Models\CandidateProfile;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCandidateProfileRequest;
use App\Http\Requests\UpdateCandidateProfileRequest;

class CandidateProfileController extends Controller
{
    public function index()
    {
        $profiles = CandidateProfile::with('user')->get();

        return response()->json([
            'success' => true,
            'message' => 'Candidate profiles retrieved successfully.',
            'data' => $profiles
        ]);
    }

    public function store(StoreCandidateProfileRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        //we need to check if the user already has a profile
        $existingProfile = CandidateProfile::where('user_id', $data['user_id'])->first();
        if ($existingProfile) {
            return response()->json([
                'success' => false,
                'message' => 'Candidate profile already exists.',
                'data' => null
            ], 409);
        }   

        $profile = CandidateProfile::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Candidate profile created successfully.',
            'data' => $profile
        ], 201);
    }

    public function show($id)
    {
        $profile = CandidateProfile::with('user')->find($id);

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Candidate profile not found.',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Candidate profile retrieved successfully.',
            'data' => $profile
        ]);
    }

    public function update(UpdateCandidateProfileRequest $request,$id)
    {
        $candidate = CandidateProfile::find($id);

        if (!$candidate) {
            return response()->json([
                'message' => 'Candidate profile not found',
                'data' => null
            ], 404);
        }
        $request->validated();
        $candidate->location = $request->input('location');
        $candidate->linkedin_profile = $request->input('linkedin_profile');
        $candidate->education = $request->input('education');
        $candidate->skills = $request->input('skills'); // ensure this is casted to array in model
        $candidate->experience_level = $request->input('experience_level');
        $candidate['user_id'] = Auth::id();
        $candidate->save();

        return response()->json([
            'message' => 'Candidate profile updated successfully',
            'data' => $candidate
        ]);
    }

    public function destroy($id)
    {
        $profile = CandidateProfile::find($id);

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Candidate profile not found.',
                'data' => null
            ], 404);
        }

        $profile->delete();

        return response()->json([
            'success' => true,
            'message' => 'Candidate profile deleted successfully.',
            'data' => null
        ]);
    }

    // CandidateProfileController.php
    public function getByUserId($userId)
    {
        $profile = CandidateProfile::with('user')->where('user_id', $userId)->first();

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Candidate profile not found.',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Candidate profile retrieved successfully.',
            'data' => $profile
        ]);
    }

}
