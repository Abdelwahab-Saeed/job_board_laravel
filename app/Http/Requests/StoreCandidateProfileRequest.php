<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCandidateProfileRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->role === 'candidate'; // adjust auth logic as needed
    }

    public function rules()
    {
        return [
            'location' => 'required|string|max:255',
            'linkedin_profile' => 'nullable|url|max:255',
            'education' => 'required|string',
            'skills' => 'required|array|min:1',
            'skills.*' => 'string|max:100',
            'experience_level' => 'required|string|in:junior,mid,senior',
        ];
    }
}
