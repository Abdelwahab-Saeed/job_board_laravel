<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employer_id',
        'job_id',
        'amount',
        'payment_method',
    ];

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }
}
