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
     * Mengambil data berita dengan filter dan pagination
     */
    public function getNews($user, array $filters, int $perPage = 10)
    {
        $query = News::with(['category', 'author', 'thumbnail', 'tags'])
            ->when($user->role !== 'admin', function ($q) use ($user) {
                // Jika bukan admin, hanya tampilkan berita milik sendiri
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
     * Menghitung statistik sederhana untuk dashboard
     */
    public function getStats($user)
    {
        $baseQuery = News::query()
            ->when($user->role !== 'admin', fn($q) => $q->where('author_id', $user->id));

        return [
            'total' => (clone $baseQuery)->count(),
            'published' => (clone $baseQuery)->where('status', 'published')->count(),
            'draft' => (clone $baseQuery)->where('status', 'draft')->count(),
            'views' => (clone $baseQuery)->sum('views_count'),
        ];
    }

    /**
     * Create News + Upload Image + Sync Tags
     */
    public function create($user, array $data, ?UploadedFile $image, array $tags = [])
    {
        return DB::transaction(function () use ($user, $data, $image, $tags) {
            // 1. Buat Berita Utama
            $news = News::create([
                'title' => $data['title'],
                'slug' => Str::slug($data['title']) . '-' . Str::random(4),
                'content' => $data['content'],
                'excerpt' => Str::limit(strip_tags($data['content']), 150),
                'category_id' => $data['category_id'],
                'author_id' => $user->id,
                'status' => $data['status'],
                'published_at' => $data['status'] === 'published' ? now() : null,
                'is_headline' => $data['is_headline'] ?? false,
            ]);

            // 2. Upload Gambar Utama (jika ada)
            if ($image) {
                $path = $image->store('news-images', 'public');
                NewsImage::create([
                    'news_id' => $news->id,
                    'image_path' => $path,
                    'is_primary' => true,
                    'sort_order' => 1
                ]);
            }

            // 3. Sync Tags (Many-to-Many)
            if (!empty($tags)) {
                $news->tags()->sync($tags);
            }

            return $news;
        });
    }

    /**
     * Update News
     */
    public function update(News $news, array $data, ?UploadedFile $newImage, array $tags = [])
    {
        return DB::transaction(function () use ($news, $data, $newImage, $tags) {
            // 1. Update Data Dasar
            $news->update([
                'title' => $data['title'],
                'content' => $data['content'],
                'excerpt' => Str::limit(strip_tags($data['content']), 150),
                'category_id' => $data['category_id'],
                'status' => $data['status'],
                'is_headline' => $data['is_headline'] ?? false,
                // Update published_at hanya jika berubah jadi published pertama kali
                'published_at' => ($news->status !== 'published' && $data['status'] === 'published')
                    ? now()
                    : $news->published_at
            ]);

            // 2. Handle Ganti Gambar
            if ($newImage) {
                // Hapus gambar lama (fisik & db)
                $oldImage = $news->thumbnail;
                if ($oldImage) {
                    if (Storage::disk('public')->exists($oldImage->image_path)) {
                        Storage::disk('public')->delete($oldImage->image_path);
                    }
                    $oldImage->delete();
                }

                // Upload baru
                $path = $newImage->store('news-images', 'public');
                NewsImage::create([
                    'news_id' => $news->id,
                    'image_path' => $path,
                    'is_primary' => true
                ]);
            }

            // 3. Sync Tags
            $news->tags()->sync($tags);

            return $news;
        });
    }

    public function delete(News $news)
    {
        DB::transaction(function () use ($news) {
            // Hapus semua gambar fisik terkait
            foreach ($news->images as $img) {
                if (Storage::disk('public')->exists($img->image_path)) {
                    Storage::disk('public')->delete($img->image_path);
                }
            }
            // Tags & Comments akan terhapus otomatis jika pakai onDelete('cascade') di migration
            // Tapi record NewsImage harus dihapus via model relation atau cascade DB
            $news->delete();
        });
    }
}
