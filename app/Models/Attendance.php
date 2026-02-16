<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public function reports()
    {
        return $this->hasMany(JobdeskReport::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
