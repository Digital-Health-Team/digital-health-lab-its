<?php

namespace App\Services\Tag;

use App\Models\Tag;
use Illuminate\Support\Str;

class TagService
{
    /**
     * Get Tags with Filters & Pagination
     */
    public function getTags(array $filters, int $perPage = 10)
    {
        // Hitung jumlah berita yang menggunakan tag ini
        $query = Tag::withCount('news');

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        return $query->orderBy('name', 'asc')->paginate($perPage);
    }

    /**
     * Create Tag
     */
    public function create(array $data)
    {
        return Tag::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);
    }

    /**
     * Update Tag
     */
    public function update(Tag $tag, array $data)
    {
        $tag->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);

        return $tag;
    }

    /**
     * Delete Tag
     */
    public function delete(Tag $tag)
    {
        // Karena ada onCascade di database migration (news_tags),
        // menghapus tag akan otomatis menghapus relasinya di pivot table.
        return $tag->delete();
    }
}
