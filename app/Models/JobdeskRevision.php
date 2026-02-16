<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobdeskRevision extends Model
{
    use HasFactory;

    // Izinkan mass assignment untuk kolom ini
    protected $guarded = ['id'];

    /**
     * Relasi ke Jobdesk (Tugas yang direvisi)
     */
    public function jobdesk()
    {
        return $this->belongsTo(Jobdesk::class);
    }

    /**
     * Relasi ke User (PM / Super Admin yang meminta revisi)
     */
    public function pm()
    {
        return $this->belongsTo(User::class, 'pm_id');
    }
}
