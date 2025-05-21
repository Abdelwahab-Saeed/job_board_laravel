<?php

namespace Database\Seeders;

use App\Models\EmployerProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmployerProfileSeeder extends Seeder
{
    public function run(): void
    {
        $employers = User::where('role', 'employer')->get();

        foreach ($employers as $employer) {
            EmployerProfile::create([
                'user_id' => $employer->id,
                'company_name' => fake()->company(),
                'company_website' => fake()->url(),
                'company_logo' => null,
                'bio' => fake()->paragraph()
            ]);
        }
    }
}