<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCandidateProfileRequest extends FormRequest
{
    public function authorize()
    {
        // Only authorize if the user is authenticated and is a candidate
        return auth()->check() && auth()->user()->role === 'candidate';
    }

    public function rules()
    {
        return [
            'location' => 'sometimes|required|string|max:255',
            'linkedin_profile' => 'sometimes|nullable|url|max:255',
            'education' => 'sometimes|required|string',
            'skills' => 'sometimes|required|array|min:1',
            'skills.*' => 'sometimes|string|max:100',
            'experience_level' => 'sometimes|required|string|in:junior,mid,senior',
        ];
    }
}
