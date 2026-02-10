<?php

namespace App\Http\Requests\News;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ambil ID dari route parameter (jika pakai Controller) atau logic Livewire
        // Disini kita definisikan rule standar, nanti ID di-inject di Livewire

        return [
            'title' => [
                'required',
                'string',
                'min:5',
                'max:255',
                // Rule unique akan kita sesuaikan di Livewire agar bisa ignore ID
                // Rule::unique('news', 'title')->ignore($this->news_id)
            ],
            'content' => ['required', 'string', 'min:20'],
            'category_id' => ['required', 'exists:categories,id'],
            'status' => ['required', 'in:draft,published,archived'],
            'is_headline' => ['boolean'],
            'photos' => ['nullable', 'array', 'max:5'], // Maksimal 5 foto (opsional)
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // Validasi per file
            'selectedTags' => ['array'],
            'selectedTags.*' => ['exists:tags,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul berita wajib diisi.',
            'title.unique' => 'Judul berita sudah digunakan oleh artikel lain.',
        ];
    }
}
