<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // Rule unique akan di-override di Livewire agar bisa ignore ID
            ],
            'role' => ['required', 'in:admin,user'],
            'password' => ['nullable', 'string', 'min:6'], // Password boleh kosong saat update
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh pengguna lain.',
            'password.min' => 'Password baru minimal 6 karakter.',
        ];
    }
}
