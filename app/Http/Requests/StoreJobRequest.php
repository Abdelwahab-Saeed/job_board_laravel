<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobRequest extends FormRequest
{
    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization()
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'You do not have permission to create jobs. Employer access required.',
                'errors' => [
                    'permissions' => ['Unauthorized action.']
                ]
            ], 403)
        );
    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // You may want to adjust authorization logic based on your app's requirements
        return auth()->check() && auth()->user()->role === 'employer';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:50'],
            'responsibilities' => ['required', 'string', 'min:30'],
            'skills' => ['required', 'string'],
            'technologies' => ['required', 'string'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'location' => ['required', 'string', 'max:255'],
            'experience_level' => ['required', 'string', Rule::in(['entry', 'junior', 'mid', 'senior'])],
            'work_type' => ['required', 'string', Rule::in(['remote', 'onsite', 'hybrid'])],
            'salary' => ['required', 'numeric', 'min:0'],
            'benefits' => ['required', 'string'],
            'deadline' => ['required', 'date', 'after:today'],
            'status' => ['sometimes', 'string', Rule::in(['pending', 'published', 'closed', 'rejected'])],
            'employer_id' => ['sometimes', 'integer', 'exists:users,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            
            'title.required' => 'The job title is required',
            'title.string' => 'The job title must be a text value',
            'title.max' => 'The job title cannot exceed 255 characters',
            

            'description.required' => 'The job description is required',
            'description.string' => 'The job description must be a text value',
            'description.min' => 'The job description should be at least 50 characters',
            
            
            'responsibilities.required' => 'The job responsibilities are required',
            'responsibilities.string' => 'The job responsibilities must be a text value',
            'responsibilities.min' => 'The job responsibilities should be at least 30 characters',
            
           
            'skills.required' => 'The required skills are required',
            'skills.string' => 'The skills must be a text value',
            
            
            'technologies.required' => 'The required technologies are required',
            'technologies.string' => 'The technologies must be a text value',
            
            
            'category_id.required' => 'The job category is required',
            'category_id.integer' => 'The category ID must be a number',
            'category_id.exists' => 'The selected category does not exist',
            
            
            'location.required' => 'The job location is required',
            'location.string' => 'The location must be a text value',
            'location.max' => 'The location cannot exceed 255 characters',
            
            
            'experience_level.required' => 'The experience level is required',
            'experience_level.string' => 'The experience level must be a text value',
            'experience_level.in' => 'The experience level must be one of: entry, junior, mid, senior',
            
            
            'work_type.required' => 'The work type is required',
            'work_type.string' => 'The work type must be a text value',
            'work_type.in' => 'The work type must be one of: remote, onsite, hybrid',
            
            
            'salary.required' => 'The salary is required',
            'salary.numeric' => 'The salary must be a number',
            'salary.min' => 'The salary must be at least 0',
            
            
            'benefits.required' => 'The job benefits are required',
            'benefits.string' => 'The benefits must be a text value',
            
            
            'deadline.required' => 'The application deadline is required',
            'deadline.date' => 'The deadline must be a valid date',
            'deadline.after' => 'The deadline must be a date after today',
            
            
            'employer_id.required' => 'The employer ID is required',
            'employer_id.integer' => 'The employer ID must be a number',
            'employer_id.exists' => 'The selected employer does not exist',
        ];
    }
}