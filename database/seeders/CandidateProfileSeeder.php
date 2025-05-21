<?php

namespace Database\Seeders;

use App\Models\CandidateProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class CandidateProfileSeeder extends Seeder
{
    public function run(): void
    {
        $candidates = User::where('role', 'candidate')->get();
        $experienceLevels = ['مبتدئ', 'متوسط', 'خبير'];

        foreach ($candidates as $candidate) {
            CandidateProfile::create([
                'user_id' => $candidate->id,
                'skills' => implode(', ', fake()->words(5)),
                'experience_level' => fake()->randomElement($experienceLevels)
            ]);
        }
    }
}