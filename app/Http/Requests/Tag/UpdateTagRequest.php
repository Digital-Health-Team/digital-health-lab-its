<?php

namespace App\Http\Requests\Tag;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                // Rule unique akan di-override di Livewire agar bisa ignore ID
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama tag wajib diisi.',
            'name.min' => 'Nama tag terlalu pendek (minimal 2 karakter).',
            'name.unique' => 'Tag dengan nama ini sudah ada.',
        ];
    }
}
