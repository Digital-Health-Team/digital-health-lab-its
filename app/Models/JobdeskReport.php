<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JobdeskReport extends Model
{
    protected $guarded = ['id'];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function jobdesk()
    {
        return $this->belongsTo(Jobdesk::class);
    }

    // Jika laporan ini spesifik untuk membalas revisi
    public function revisionThread()
    {
        return $this->belongsTo(RevisionThread::class);
    }

    public function details()
    {
        return $this->hasMany(ReportDetail::class);
    }

    // Attachments (Bukti Kerja)
    public function attachments()
    {
        return $this->morphMany(MediaAttachment::class, 'attachable');
    }
}
