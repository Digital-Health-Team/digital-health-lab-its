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
        if (!$this->thumbnail_path) {
            return null;
        }

        if (str_starts_with($this->thumbnail_path, 'assets/')) {
            return '/' . $this->thumbnail_path;
        }

        return Storage::disk('public')->url($this->thumbnail_path);
    }

    public function getPdfUrlAttribute(): ?string
    {
        return $this->pdf_path
            ? Storage::disk('public')->url($this->pdf_path)
            : null;
    }
}
