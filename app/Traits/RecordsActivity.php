<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait RecordsActivity
{
    /**
     * Otomatis mendaftarkan event listener saat model di-boot.
     */
    protected static function bootRecordsActivity()
    {
        // Otomatis catat saat Create, Update, dan Delete
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }

    /**
     * Fungsi utama untuk mencatat log ke database.
     */
    public function recordActivity(string $action)
    {
        $oldData = null;
        $newData = null;

        if ($action === 'created') {
            $newData = $this->toArray();
        } elseif ($action === 'updated') {
            // Hanya ambil kolom yang berubah untuk menghemat space database
            $newData = $this->getChanges();
            $oldData = array_intersect_key($this->getOriginal(), $newData);
        } elseif ($action === 'deleted') {
            $oldData = $this->toArray();
        }

        $this->activityLogs()->create([
            'user_id' => Auth::id(),
            'action' => $action,
            'old_data' => empty($oldData) ? null : $oldData,
            'new_data' => empty($newData) ? null : $newData,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Fungsi manual untuk mencatat aksi "Read" (Dilihat).
     */
    public function logRead()
    {
        $this->recordActivity('read');
    }

    /**
     * Relasi ke model ActivityLog
     */
    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'loggable');
    }
}
