<?php

namespace App\Models;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    use HasFactory, RecordsActivity;

    protected $fillable = [
        'attachable_type',
        'attachable_id',
        'file_url',
        'file_name',
        'file_size',
        'file_type',
        'is_primary',
        'sort_order',
        'uploaded_by',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getPublicUrlAttribute(): ?string
    {
        return self::resolveUrl($this->file_url);
    }

    /**
     * `file_url` holds two different path shapes and always has: seeded rows store a
     * public-root path ("assets/images/projects/x.png") while uploads store a path on
     * the public disk ("open_source_projects/x.png"). Running the former through
     * Storage::url() yields "/storage/assets/..." — a 404, which is why covers silently
     * fell back to placeholder gradients. Every caller must go through here.
     */
    public static function resolveUrl(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        if (str_starts_with($path, 'http') || str_starts_with($path, '/')) {
            return $path;
        }

        if (str_starts_with($path, 'assets/')) {
            return '/'.$path;
        }

        $url = Storage::disk('public')->url($path);

        // The public disk's url is built from APP_URL, so it comes back absolute
        // ("http://localhost/storage/…") and 404s on any other host or port. Same trap
        // BuildKnowledgeIndexAction documents for route(): relative works everywhere.
        // A disk pointed at a CDN keeps its absolute URL — only our own host is stripped.
        if (parse_url($url, PHP_URL_HOST) === parse_url(config('app.url'), PHP_URL_HOST)) {
            return parse_url($url, PHP_URL_PATH) ?: $url;
        }

        return $url;
    }
}
