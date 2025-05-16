<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    /** @use HasFactory<\Database\Factories\JobFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'responsibilities',
        'skills',
        'technologies',
        'category_id',
        'location',
        'experience_level',
        'work_type',
        'salary_range',
        'benefits',
        'deadline',
        'status',
        'employer_id',
    ];

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function employer() {
        return $this->belongsTo(User::class, 'employer_id', 'id');
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
