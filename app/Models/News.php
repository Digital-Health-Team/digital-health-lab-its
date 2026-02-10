<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'status',
        'is_headline',
        'is_breaking',
        'views_count',
        'monthly_views',
        'daily_views',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_headline' => 'boolean',
        'is_breaking' => 'boolean',
    ];

    // --- Relationships ---

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function images()
    {
        return $this->hasMany(NewsImage::class)->orderBy('sort_order');
    }

    // Helper untuk ambil gambar utama saja
    public function thumbnail()
    {
        return $this->hasOne(NewsImage::class)->where('is_primary', true);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'news_tags');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->where('is_approved', true);
    }

    // --- Scopes (Query Helpers) ---

    // Ambil hanya yang status Published
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    // Ambil Headline
    public function scopeHeadline($query)
    {
        return $query->published()->where('is_headline', true);
    }

    // Ambil Trending (Berdasarkan view harian)
    public function scopeTrending($query, $limit = 5)
    {
        return $query->published()
            ->orderBy('daily_views', 'desc')
            ->limit($limit);
    }

    // Ambil Populer Bulan Ini
    public function scopePopularMonthly($query, $limit = 5)
    {
        return $query->published()
            ->orderBy('monthly_views', 'desc')
            ->limit($limit);
    }

    /**
     * Increment views counters dengan Session blocking
     * agar 1 user tidak spam refresh page.
     */
    public function incrementViews(News $news)
    {
        $sessionKey = 'viewed_news_' . $news->id;

        // Cek apakah user ini sudah melihat berita ini dalam sesi browser saat ini
        if (!session()->has($sessionKey)) {

            // Gunakan increment() bawaan Laravel (Atomic Operation)
            // Ini lebih aman daripada $news->views + 1
            $news->increment('views_count');
            $news->increment('monthly_views');
            $news->increment('daily_views');

            // Simpan penanda di session bahwa user sudah baca berita ini
            session()->put($sessionKey, true);
        }
    }
}
