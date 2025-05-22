<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Payment;
use App\Models\Job;

class StripeController extends Controller
{
    public function createCheckoutSession(Request $request, $jobId)
    {
        $job = Job::findOrFail($jobId);

        if ($job->employer_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.5',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $appId = $request->input('application_id');
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Job Posting: ' . $job->title,
                        ],
                        'unit_amount' => $request->amount * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => 'http://localhost:5173/payment-success/?job_id=' . $job->id . '&application_id=' . $appId,
                'cancel_url' => 'http://localhost:5173/payment-cancel/?job_id=' . $job->id . '&application_id=' . $appId,
                'metadata' => [
                    'job_id' => $job->id,
                    'employer_id' => auth()->id(),
                    'amount' => $request->amount,
                ],
            ]);

            return response()->json(['id' => $session->id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->server('HTTP_STRIPE_SIGNATURE');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            if (!Payment::where('stripe_session_id', $session->id)->exists()) {
                Payment::create([
                    'employer_id' => $session->metadata->employer_id,
                    'job_id' => $session->metadata->job_id,
                    'amount' => $session->metadata->amount,
                    'payment_method' => 'stripe',
                    'stripe_session_id' => $session->id,
                ]);
            }
        }

        return response()->json(['status' => 'success']);
    }
}
/*************  ✨ Windsurf Command ⭐  *************/
/*******  e8e11949-2c93-47c1-911a-aab474d50439  *******/    /**

     * Redirects to the employer's dashboard with a query string parameter

     * of `payment=cancel` to indicate that the payment was cancelled.

     *

     * @return \Illuminate\Http\RedirectResponse

     */
