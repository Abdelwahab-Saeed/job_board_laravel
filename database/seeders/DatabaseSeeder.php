<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            EmployerProfileSeeder::class,
            CandidateProfileSeeder::class,
            JobSeeder::class,
            CommentSeeder::class,
            ApplicationSeeder::class,
            PaymentSeeder::class,
            // SampleDataSeeder::class // يمكن استخدامه لبيانات اختبار إضافية
        ]);
    }
}