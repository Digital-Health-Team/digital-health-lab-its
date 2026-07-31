<?php

namespace App\Models;

use App\Traits\HasEnglishOverlay;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'user_id',
        'title',
        'slug',
        'author',
        'category',
        'status',
        'validated_by',
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
        'withdrawal_requested_at',
    ];

    protected $casts = [
        'withdrawal_requested_at' => 'datetime',
        'description' => 'array',
        'keywords' => 'array',
        'is_free_access' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'description_en' => 'array',
        'keywords_en' => 'array',
    ];

    /** Null for admin-authored site content; set for student submissions. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * The public gate. Every guest-facing query goes through this.
     *
     * Deliberately NOT a global scope — the admin moderation queue must see pending
     * and rejected rows, and an explicit ->approved() keeps the filter visible to
     * anyone reading the controller.
     */
    public function scopeApproved(Builder $query): void
    {
        $query->where('status', 'approved');
    }

    /** Seeded rows hold a public-root path, uploads hold a disk path — Attachment::resolveUrl knows both. */
    public function getThumbnailUrlAttribute(): ?string
    {
        return Attachment::resolveUrl($this->thumbnail_path);
    }

    public function getPdfUrlAttribute(): ?string
    {
        return Attachment::resolveUrl($this->pdf_path);
    }

    /** Row shape shared by the Research list page and the detail page's related list. */
    public function toListArray(): array
    {
        return [
            'id' => (string) $this->id,
            'title' => $this->localized('title'),
            'slug' => $this->slug,
            'author' => $this->author,
            'category' => $this->category,
            'thumbnailUrl' => $this->thumbnail_url ?? '',
            'publishedAt' => $this->published_at?->toISOString() ?? $this->created_at->toISOString(),
            'viewCount' => $this->view_count,
            'href' => route('publications.show', $this->slug),
            'journal' => $this->journal,
            'pmid' => $this->pmid,
            'isFreeAccess' => $this->is_free_access,
        ];
    }
}
