<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'resume_snapshot' => 'required|string|max:255',
            'cover_letter' => 'nullable|string|max:1000',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'resume_snapshot.required' => 'Resume is required.',
            'contact_email.required' => 'Email is required.',
            'contact_email.email' => 'Email must be valid.',
            'contact_phone.required' => 'Phone number is required.',
        ];
    }
}
