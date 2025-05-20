<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
       
        return auth()->check() && auth()->user()->role === 'employer';
    }

   public function rules(): array
{
    return [
        'amount' => 'required|numeric|min:0',
        'payment_method' => 'required|string|max:255',
    ];
}

}
