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

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Laporan
    public function reports()
    {
        return $this->hasMany(JobdeskReport::class);
    }

    // [BARU] Relasi ke Media Attachments (Polymorphic)
    public function attachments()
    {
        return $this->morphMany(MediaAttachment::class, 'attachable');
    }

    // [HELPER] Ambil Selfie Check In dari Attachments
    public function getSelfieInAttribute()
    {
        // Cari attachment yang nama filenya mengandung 'checkin'
        return $this->attachments->first(fn($att) => str_contains($att->file_name, 'checkin'))?->file_path;
    }

    // [HELPER] Ambil Selfie Check Out dari Attachments
    public function getSelfieOutAttribute()
    {
        // Cari attachment yang nama filenya mengandung 'checkout'
        return $this->attachments->first(fn($att) => str_contains($att->file_name, 'checkout'))?->file_path;
    }
}
