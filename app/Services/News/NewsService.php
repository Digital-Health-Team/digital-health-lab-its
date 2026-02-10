<?php

namespace App\Services\News;

use App\Models\News;
use App\Models\NewsImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class NewsService
{
    /**
     * Get News (Pagination & Filter)
     */
    public function getNews($user, array $filters, int $perPage = 10)
    {
        $query = News::with(['category', 'author', 'thumbnail', 'tags'])
            ->when($user->role !== 'admin', function ($q) use ($user) {
                $q->where('author_id', $user->id);
            });

        if (!empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate($perPage);
    }
    /**
     * 1. Get Stats (Total, Published, Draft, Total Views)
     */
    public function getStats($user)
    {
        // Buat base query
        $query = News::query();

        // Jika BUKAN admin, filter hanya berita milik user tersebut
        if ($user->role !== 'admin') {
            $query->where('author_id', $user->id);
        }

        // Menggunakan clone agar query base tidak berubah saat ditambah where clause lain
        return [
            'total' => (clone $query)->count(),
            'published' => (clone $query)->where('status', 'published')->count(),
            'draft' => (clone $query)->where('status', 'draft')->count(),
            // Menjumlahkan kolom views_count
            'views' => (clone $query)->sum('views_count'),
        ];
    }

    /**
     * 2. Ambil 5 Berita Terbaru milik User
     */
    public function getUserRecentNews($userId)
    {
        return News::with('category') // Eager load kategori agar query ringan
            ->where('author_id', $userId)
            ->latest() // Order by created_at desc
            ->take(5)  // Limit 5
            ->get();
    }

    /**
     * 3. Ambil 5 Berita Terpopuler milik User (Berdasarkan Views)
     */
    public function getUserPopularNews($userId)
    {
        return News::where('author_id', $userId)
            ->where('status', 'published') // Hanya ambil yang sudah publish
            ->orderBy('views_count', 'desc') // Urutkan view terbanyak
            ->take(5) // Limit 5
            ->get();
    }

    /**
     * Helper: Hapus 1 Gambar (Fisik & DB)
     */
    public function deleteSingleImage(NewsImage $image)
    {
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }
        $image->delete();
    }

    /**
     * Helper: Upload & Append Gambar
     */
    public function uploadImages(News $news, array $photos)
    {
        $lastOrder = $news->images()->max('sort_order') ?? 0;
        $hasPrimary = $news->images()->where('is_primary', true)->exists();

        foreach ($photos as $index => $photo) {
            $path = $photo->store('news-images', 'public');
            NewsImage::create([
                'news_id' => $news->id,
                'image_path' => $path,
                'is_primary' => (!$hasPrimary && $index === 0),
                'sort_order' => $lastOrder + $index + 1
            ]);
        }
    }

    public function create($user, array $data, array $photos = [], array $tags = [])
    {
        return DB::transaction(function () use ($user, $data, $photos, $tags) {
            $publishedAt = ($data['status'] === 'published') ? now() : null;

            $news = News::create([
                'title' => $data['title'],
                'slug' => Str::slug($data['title']) . '-' . Str::random(4),
                'content' => $data['content'],
                'excerpt' => Str::limit(strip_tags($data['content']), 150),
                'category_id' => $data['category_id'],
                'author_id' => $user->id,
                'status' => $data['status'],
                'date_occurred' => $data['date_occurred'],
                'published_at' => $publishedAt,
                // Flags
                'is_headline' => $data['is_headline'] ?? false,
                'is_breaking' => $data['is_breaking'] ?? false, // <--- TAMBAHAN
            ]);

            if (!empty($photos))
                $this->uploadImages($news, $photos);
            if (!empty($tags))
                $news->tags()->sync($tags);

            return $news;
        });
    }

    public function update(News $news, array $data, array $newPhotos = [], array $tags = [])
    {
        return DB::transaction(function () use ($news, $data, $newPhotos, $tags) {
            $publishedAt = ($data['status'] === 'published') ? now() : null;

            $news->update([
                'title' => $data['title'],
                'content' => $data['content'],
                'excerpt' => Str::limit(strip_tags($data['content']), 150),
                'category_id' => $data['category_id'],
                'date_occurred' => $data['date_occurred'],
                'status' => $data['status'],
                'published_at' => $publishedAt,
                // Flags
                'is_headline' => $data['is_headline'] ?? false,
                'is_breaking' => $data['is_breaking'] ?? false, // <--- TAMBAHAN
            ]);

            if (!empty($newPhotos))
                $this->uploadImages($news, $newPhotos);

            $news->tags()->sync($tags);

            return $news;
        });
    }

    public function delete(News $news)
    {
        DB::transaction(function () use ($news) {
            foreach ($news->images as $img) {
                if (Storage::disk('public')->exists($img->image_path)) {
                    Storage::disk('public')->delete($img->image_path);
                }
            }
            $news->delete();
        });
    }
}
