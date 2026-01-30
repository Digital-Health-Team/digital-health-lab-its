<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    protected $fillable = ['internship_period_id', 'date', 'activity', 'proof_file_path', 'status', 'feedback'];
    
    protected $casts = [
        'date' => 'date',
    ];

    public function internshipPeriod()
    {
        return $this->belongsTo(InternshipPeriod::class);
    }
}
