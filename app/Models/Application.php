<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Job;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'job_id',
        'resume_snapshot',
        'cover_letter',
        'contact_email',
        'contact_phone',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function candidate()
{
    return $this->belongsTo(Candidate::class);
}
}



