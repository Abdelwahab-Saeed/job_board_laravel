<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;
use App\Models\Job;

class PaymentController extends Controller
{
   
    public function store(StorePaymentRequest $request, $job_id)
    {
        $job = Job::findOrFail($job_id);

       
        if ($job->employer_id !== auth()->id()) {
            return response()->json(['message' => 'You are not authorized to pay for this job.'], 403);
        }

        $payment = Payment::create([
            'employer_id' => auth()->id(),
            'job_id' => $job->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
        ]);

        return response()->json([
            'message' => 'Payment created successfully.',
            'data' => $payment
        ], 201);
    }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        if ($payment->employer_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $payment->update($request->only(['amount', 'payment_method']));

        return response()->json([
            'message' => 'Payment updated successfully.',
            'data' => $payment,
        ]);
    }

  
    public function destroy(Payment $payment)
    {
        if ($payment->employer_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $payment->delete();

        return response()->json([
            'message' => 'Payment deleted successfully.',
        ]);
    }
}
