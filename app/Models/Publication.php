<?php

namespace App\Models;

use App\Traits\HasEnglishOverlay;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Publication extends Model
{
    use HasEnglishOverlay, HasFactory;

    /** Attributes with an `<attr>_en` sibling column — see HasEnglishOverlay. */
    protected array $localizable = [
        'title',
        'abstract',
        'description',
        'keywords',
    ];

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
        'title_en',
        'abstract_en',
        'description_en',
        'keywords_en',
    ];

    protected $casts = [
        'description' => 'array',
        'keywords' => 'array',
        'is_free_access' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'description_en' => 'array',
        'keywords_en' => 'array',
    ];

    public function getThumbnailUrlAttribute(): ?string
    {
        if (! $this->thumbnail_path) {
            return null;
        }

        if (str_starts_with($this->thumbnail_path, 'assets/')) {
            return '/'.$this->thumbnail_path;
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
