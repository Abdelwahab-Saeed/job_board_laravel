<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $jobs = Job::all();
        $candidates = User::where('role', 'candidate')->get();
        $statuses = ['pending', 'reviewed', 'accepted', 'rejected'];

        foreach ($jobs as $job) {
            $applicationsCount = rand(1, 10);
            
            for ($i = 0; $i < $applicationsCount; $i++) {
                $candidate = $candidates->random();
                
                // إنشاء خطاب تغطية قصير
                $coverLetter = fake()->boolean(70) 
                    ? Str::limit(fake()->paragraphs(2, true), 200) 
                    : null;
                
                Application::create([
                    'candidate_id' => $candidate->id,
                    'job_id' => $job->id,
                    'resume_snapshot' => '/resumes/sample.pdf',
                    'cover_letter' => $coverLetter,
                    'contact_email' => $candidate->email,
                    'contact_phone' => fake()->phoneNumber(),
                    'status' => fake()->randomElement($statuses)
                ]);
            }
        }
    }
}