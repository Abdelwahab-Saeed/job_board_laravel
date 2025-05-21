<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $applications = Application::all();
        $paymentMethods = ['credit_card', 'paypal', 'bank_transfer'];

        foreach ($applications as $application) {
            if (fake()->boolean(30)) { // 30% chance of having a payment
                Payment::create([
                    'employer_id' => $application->job->employer_id,
                    'job_id' => $application->job_id,
                    'amount' => fake()->randomFloat(2, 50, 500),
                    'payment_method' => fake()->randomElement($paymentMethods)
                ]);
            }
        }
    }
}