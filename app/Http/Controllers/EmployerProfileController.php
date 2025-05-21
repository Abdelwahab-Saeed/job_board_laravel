<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployerProfileRequest;
use App\Http\Requests\UpdateEmployerProfileRequest;
use App\Models\EmployerProfile;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployerProfileController extends Controller
{
    public function show()
    {
        $profile = EmployerProfile::where('user_id', Auth::id())->with('user')->first();

        if (!$profile) {
            return response()->json(['message' => 'Profile not found.'], 404);
        }

        // $profile->user = Auth::user(); 
        return response()->json([
            'success' => true,
            'data' => $profile
        ]);
    }

    public function store(StoreEmployerProfileRequest $request)
    {
        $data = $request->only(['company_name', 'company_website', 'bio']);
        $data['user_id'] = Auth::id();
        

        //we need to cehck if the user already has a profile
        $existingProfile = EmployerProfile::where('user_id', Auth::id())->first();
        if ($existingProfile) {
            return response()->json(['message' => 'Profile already exists.'], 400);
        }

        $image = $request->file('company_logo');

        if ($image) {
            // Create a simpler filename
            $filename = time() . '_' . Str::slug($request->company_name) . '.' . $image->getClientOriginalExtension();
            
            // Directly move the file to public folder for immediate access
            $image->move(public_path('logos'), $filename);
            
            // Store the complete URL for API access
            $data['company_logo'] = url('logos/' . $filename);
        } else {
            return response()->json(['message' => 'Company logo is required.'], 400);
        }
        
        $profile = EmployerProfile::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Company profile created successfully',
            'data' => $profile
        ], 201);
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'sometimes|required|string|max:255',
            'company_website' => 'nullable|url',
            'company_logo' => 'nullable|image|max:2048',
            'bio' => 'nullable|string',
        ]);

        $profile = EmployerProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'company_name' => $request->company_name,
                'company_website' => $request->company_website,
                'bio' => $request->bio,
            ]
        );

        if ($request->hasFile('company_logo')) {
            $path = $request->file('company_logo')->store('logos', 'public');
            $profile->company_logo = $path;
            $profile->save();
        }

        return response()->json($profile);
    }
}