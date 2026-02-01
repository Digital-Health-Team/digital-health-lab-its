<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LecturerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nidn',
        'position',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function internshipPeriods()
    {
        return $this->hasMany(InternshipPeriod::class, 'lecturer_id');
    }
}
