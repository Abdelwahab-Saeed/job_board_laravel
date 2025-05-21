<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Employer;
use App\Models\Candidate;
use App\Models\Job;
use App\Models\Comment;
use App\Models\Application;
use App\Models\JobAnalytics;
use App\Models\JobFilter;
use App\Models\Payment;




class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $employerUser = User::create([
            'name' => 'Company A',
            'email' => 'employer@example.com',
            'password' => Hash::make('password'),
            'role' => 'employer',
        ]);

        $employer = Employer::create([
            'user_id' => $employerUser->id,
            'company_name' => 'Company A',
            'company_website' => 'https://companya.com',
            'bio' => 'We are a software company.',
        ]);

        $candidateUser = User::create([
            'name' => 'Candidate A',
            'email' => 'candidate@example.com',
            'password' => Hash::make('password'),
            'role' => 'candidate',
        ]);

        Candidate::create([
            'user_id' => $candidateUser->id,
            'resume' => '/resumes/candidate-a.pdf',
            'linkedin_profile' => 'https://linkedin.com/in/candidate-a',
            'phone_number' => '01000000000',
            'experience_level' => 'junior',
            'location' => 'Cairo',
        ]);
       

        Job::create([
            'employer_id' => $employer->id,
            'title' => 'Frontend Developer',
            'description' => 'Build modern UI with Vue.',
            'responsibilities' => 'Develop and maintain frontend.',
            'skills' => 'Vue, JavaScript, CSS',
            'category' => 'programming',
            'location' => 'Remote',
            'technologies' => 'Vue.js, Pinia, Tailwind',
            'work_type' => 'remote',
            'salary_range' => '2000-4000',
            'benefits' => 'Health insurance, Bonuses',
            'deadline' => '2025-06-01',
            'status' => 'pending'
        ]);


        Comment::create([
            'job_id' => 1,
             'user_id' => 1,
            'comment_text' => 'Comment from admin on the first job'
          ]);
      

Application::create([
    'job_id' => 1,          
    'candidate_id' => 1,      
    'contact_email' => 'candidate@example.com',
    'contact_phone' => '01012345678',
    'status' => 'pending'
]);

Application::create([
    'job_id' => 1,
    'candidate_id' => 1,
    'contact_email' => 'candidate@example.com',
    'contact_phone' => '01012345678',
    'status' => 'accepted'
]);

JobAnalytics::create([
    'job_id' => 1,
    'views_count' => 45,
    'applications_count' => 2,
]);

JobFilter::create([
    'user_id' => 1,
    'filters_json' => json_encode([
        'category' => 'programming',
        'location' => 'remote',
        'work_type' => 'full-time'
    ]),
]);

Payment::create([
    'application_id' => 1,
    'amount' => 99.99,
    'payment_method' => 'paypal',
    'status' => 'paid'
]);


  
    }
}
