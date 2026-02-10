<?php

namespace App\Services\Category;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryService
{
    /**
     * Get Categories with Filters & Pagination
     */
    public function getCategories(array $filters, int $perPage = 10)
    {
        // Kita gunakan withCount('news') agar tahu jumlah berita di kategori ini
        $query = Category::withCount('news');

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        // Sorting berdasarkan nama agar rapi
        return $query->orderBy('name', 'asc')->paginate($perPage);
    }

    /**
     * Get All Categories for Dropdown (Helper)
     */
    public function getAllForDropdown()
    {
        return Category::orderBy('name')->get();
    }

    /**
     * Create Category
     */
    public function create(array $data)
    {
        return Category::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']), // Generate Slug otomatis
        ]);
    }

    /**
     * Update Category
     */
    public function update(Category $category, array $data)
    {
        $category->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']), // Regenerate slug jika nama berubah
        ]);

        return $category;
    }

    /**
     * Delete Category
     */
    public function delete(Category $category)
    {
        return $category->delete();
    }
}
