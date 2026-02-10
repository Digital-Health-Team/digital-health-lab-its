<?php

namespace App\Http\Requests\News;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:5', 'max:255', 'unique:news,title'],
            'content' => ['required', 'string', 'min:20'],
            'category_id' => ['required', 'exists:categories,id'],
            'status' => ['required', 'in:draft,published,archived'],
            'date_occurred' => ['required', 'date', 'before_or_equal:today'], // Validasi Tanggal
            'is_headline' => ['boolean'],
            // Validasi Array Foto
            'photos' => ['nullable', 'array', 'max:5'],
            // Validasi Per File: Image, Max 1MB (1024 KBk)
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            'selectedTags' => ['array'], // Tag opsional tapi harus array
            'selectedTags.*' => ['exists:tags,id'], // Pastikan tiap tag ID valid
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul berita wajib diisi.',
            'title.min' => 'Judul terlalu pendek (minimal 5 karakter).',
            'title.unique' => 'Judul berita ini sudah ada.',
            'content.required' => 'Konten berita wajib diisi.',
            'content.min' => 'Konten terlalu pendek, tulislah lebih detail.',
            'category_id.required' => 'Silakan pilih kategori berita.',
            'thumbnail.image' => 'File harus berupa gambar.',
            'thumbnail.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
