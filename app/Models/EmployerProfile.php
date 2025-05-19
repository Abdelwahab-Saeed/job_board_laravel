<?php

// app/Models/EmployerProfile.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'company_website',
        'company_logo',
        'bio',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
