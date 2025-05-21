<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'تطوير البرمجيات',
            'تصميم جرافيك',
            'التسويق الرقمي',
            'الموارد البشرية',
            'المحاسبة',
            'الدعم الفني',
            'إدارة المشاريع',
            'التحليل البيانات',
            'الأمن السيبراني',
            'التعليم'
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}