<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Tentukan apakah user boleh melakukan request ini.
     */
    public function authorize(): bool
    {
        // Ubah true agar semua user (yang sudah login) bisa akses,
        // atau tambahkan logic cek role admin disini.
        return true;
    }

    /**
     * Aturan validasi.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'unique:categories,name', // Nama kategori tidak boleh sama
            ],
        ];
    }

    /**
     * Pesan error kustom (Opsional, tapi bagus untuk UX).
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.min' => 'Nama kategori minimal 3 karakter.',
            'name.unique' => 'Nama kategori ini sudah ada, gunakan nama lain.',
        ];
    }
}
