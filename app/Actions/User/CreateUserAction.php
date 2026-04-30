<?php

namespace App\Actions\User;

use App\DTOs\User\UserData;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Attachment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;

class CreateUserAction
{
    public function execute(UserData $data): User
    {
        return DB::transaction(function () use ($data) {
            // 1. Buat User Core
            $user = User::create([
                'role_id' => $data->role_id,
                'email' => $data->email,
                'password' => Hash::make($data->password),
                'is_active' => true,
            ]);

            // 2. Buat Profil User
            UserProfile::create([
                'user_id' => $user->id,
                'full_name' => $data->full_name,
                'phone' => $data->phone,
                'address' => $data->address,
                'nik' => $data->nik,
                'nim' => $data->nim,
                'department' => $data->department,
                'faculty' => $data->faculty,
                'university' => $data->university,
            ]);

            // 3. Simpan Foto Profil (Jika Ada)
            if ($data->profile_photo instanceof UploadedFile) {
                $path = $data->profile_photo->store('profile_pictures', 'public');
                Attachment::create([
                    'attachable_type' => User::class,
                    'attachable_id' => $user->id,
                    'file_url' => $path,
                    'file_type' => $data->profile_photo->getClientMimeType(),
                    'is_primary' => true,
                    'uploaded_by' => auth()->id() ?? $user->id,
                ]);
            }

            return $user;
        });
    }
}
