<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Kita ambil ID kategori yang sedang diedit dari route atau input hidden
        // Jika di Livewire, kita biasanya passing ID secara manual, jadi validasi ini
        // akan kita panggil secara spesifik di dalam Component.

        // Namun, untuk standar Form Request Laravel Controller:
        $categoryId = $this->route('category') ? $this->route('category')->id : $this->id;

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                // Cek unique, tapi abaikan (ignore) ID kategori ini sendiri
                // Supaya kalau tidak ganti nama, tidak dianggap error "nama sudah ada"
                Rule::unique('categories', 'name')->ignore($categoryId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah digunakan.',
        ];
    }
}
