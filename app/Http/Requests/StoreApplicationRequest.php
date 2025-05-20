<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'candidate'; 
    }

    public function rules(): array
    {
        return [
            'resume_snapshot' => 'required|file|mimes:pdf,doc,docx|max:5120', 
            'cover_letter' => 'nullable|string|max:5000', 
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
           
            
        ];
    }

    public function messages(): array
    {
        return [
            'resume_snapshot.required' => 'Resume is required.',
            'resume_snapshot.file' => 'Resume must be a file.',
            'resume_snapshot.mimes' => 'Resume must be a PDF or Word document.',
            'resume_snapshot.max' => 'Resume size must not exceed 5MB.',
            'cover_letter.max' => 'Cover letter must not exceed 5000 characters.',
            'contact_email.required' => 'Email is required.',
            'contact_email.email' => 'Please enter a valid email address.',
            'contact_phone.required' => 'Phone number is required.',
      
        ];
    }
}