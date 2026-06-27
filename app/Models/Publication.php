<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'author',
        'category',
        'abstract',
        'description',
        'keywords',
        'doi',
        'journal',
        'pmid',
        'thumbnail_path',
        'pdf_path',
        'pdf_file_size',
        'view_count',
        'is_free_access',
        'is_featured',
        'published_at',
    ];

    protected $casts = [
        'description' => 'array',
        'keywords' => 'array',
        'is_free_access' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail_path
            ? Storage::disk('public')->url($this->thumbnail_path)
            : null;
    }

    public function getPdfUrlAttribute(): ?string
    {
        return $this->pdf_path
            ? Storage::disk('public')->url($this->pdf_path)
            : null;
    }
}
