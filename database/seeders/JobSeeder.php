<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        $employers = User::where('role', 'employer')->get();
        $categories = Category::all();
        $workTypes = ['دوام كامل', 'دوام جزئي', 'عن بعد', 'هجين'];
        $statuses = ['pending', 'approved', 'rejected'];

        foreach ($employers as $employer) {
            $jobsCount = rand(1, 5);
            
            for ($i = 0; $i < $jobsCount; $i++) {
                Job::create([
                    'employer_id' => $employer->id,
                    'title' => fake()->jobTitle(),
                    'description' => fake()->paragraphs(3, true),
                    'responsibilities' => implode("\n", fake()->sentences(5)),
                    'skills' => implode(', ', fake()->words(5)),
                    'technologies' => implode(', ', fake()->words(3)),
                    'category_id' => $categories->random()->id,
                    'location' => fake()->city(),
                    'experience_level' => fake()->randomElement(['مبتدئ', 'متوسط', 'خبير']),
                    'work_type' => fake()->randomElement($workTypes),
                    'benefits' => implode("\n", fake()->sentences(3)),
                    'deadline' => fake()->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
                    'status' => fake()->randomElement($statuses)
                ]);
            }
        }
    }
}