<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => ' User',
            'email' => 'harbi@example.com',
            'password' => Hash::make('password'),
            'role' => 'employer',
            'profile_picture' => null,
            'phone' => '01000000000' 
            
        ]);

        User::factory(5)->create([
            'role' => 'employer'
        ]);

        User::factory(20)->create([
            'role' => 'candidate'
        ]);
    }
}