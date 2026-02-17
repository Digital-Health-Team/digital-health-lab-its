<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Jobdesk extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'deadline_task' => 'datetime',
        'submitted_at' => 'datetime', // [BARU]
        'completed_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function reports()
    {
        return $this->hasMany(JobdeskReport::class);
    }

    public function revisionThreads()
    {
        return $this->hasMany(RevisionThread::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // [BARU] Helper untuk cek apakah tugas ini telat
    public function getIsLateAttribute()
    {
        if ($this->submitted_at && $this->deadline_task) {
            return $this->submitted_at->gt($this->deadline_task);
        }
        // Jika belum submit tapi sudah lewat deadline
        if (!$this->submitted_at && $this->deadline_task && now()->gt($this->deadline_task)) {
            return true;
        }
        return false;
    }

    // [BARU] Helper Text Durasi Telat (misal: "2 Hours", "1 Day")
    public function getLateDurationTextAttribute()
    {
        if (!$this->deadline_task)
            return '-';

        $compareTime = $this->submitted_at ?? now();

        if ($compareTime->gt($this->deadline_task)) {
            return $this->deadline_task->diffForHumans($compareTime, true) . ' Late';
        }

        return null;
    }
}
