<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        
        $payment = $this->route('payment'); 

        return auth()->check()
            && auth()->user()->role === 'employer'
            && $payment
            && $payment->employer_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            'amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:255',
        ];
    }
}
