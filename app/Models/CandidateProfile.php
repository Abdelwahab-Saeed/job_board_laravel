<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'location',
        'linkedin_profile',
        'title',
        'profile_photo',
        'experience_level',
        'skills',
        'experience',
        'education',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
