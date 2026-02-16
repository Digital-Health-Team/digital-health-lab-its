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

    protected $casts = [
        'deadline_task' => 'datetime',
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

    public function revisions()
    {
        return $this->hasMany(JobdeskRevision::class);
    }
}
