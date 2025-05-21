<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $jobs = Job::all();
        $users = User::all();

        foreach ($jobs as $job) {
            $commentsCount = rand(1, 5);
            
            for ($i = 0; $i < $commentsCount; $i++) {
                Comment::create([
                    'job_id' => $job->id,
                    'user_id' => $users->random()->id,
                    'content' => fake()->paragraph()
                ]);
            }
        }
    }
}