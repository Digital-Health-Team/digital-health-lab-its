<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Jobdesk extends Model
{
    use HasTranslations;

    protected $guarded = ['id'];

    // Tentukan kolom mana yang bisa ditranslate
    public $translatable = ['title', 'description'];

    // --- TAMBAHKAN/PERBAIKI BAGIAN INI ---
    protected $casts = [
        'title' => 'array',       // PENTING: Agar $task->title['id'] bisa diakses
        'description' => 'array',
        'deadline_task' => 'datetime', // Agar fungsi diffInDays() berjalan lancar
        'deadline_revision' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function revisions()
    {
        return $this->hasMany(JobdeskRevision::class);
    }

    public function revisionThreads()
    {
        return $this->hasMany(RevisionThread::class)->latest(); // Chat terbaru di atas/bawah (sesuai selera)
    }
}
