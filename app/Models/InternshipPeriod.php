<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternshipPeriod extends Model
{
    protected $fillable = ['student_id', 'lecturer_id', 'company_name', 'start_date', 'end_date', 'status'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function logbooks()
    {
        return $this->hasMany(Logbook::class);
    }
}
