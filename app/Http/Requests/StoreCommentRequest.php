<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }


  public function rules(): array
{
    return [
        'content' => 'required|string|max:1000',
    ];
}


    public function messages(): array
    {
        return [
            'user_id.required' => 'User is required',
            'user_id.exists' => 'User must exist in the database',
            'content.required' => 'Comment content is required',
            'content.string' => 'Comment must be a string',
            'content.max' => 'Comment cannot exceed 1000 characters',
        ];
    }
}
