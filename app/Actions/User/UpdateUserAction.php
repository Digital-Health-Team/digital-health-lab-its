<?php

namespace App\Actions\User;

use App\DTOs\User\UserData;
use App\Models\User;
use App\Models\Attachment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class UpdateUserAction
{
    public function execute(User $user, UserData $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            // 1. Update User Core
            $user->update([
                'role_id' => $data->role_id,
                'email' => $data->email,
                'password' => $data->password ? Hash::make($data->password) : $user->password,
            ]);

            // 2. Update atau Buat Profil
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'full_name' => $data->full_name,
                    'phone' => $data->phone,
                    'address' => $data->address,
                    'nik' => $data->nik,
                    'nim' => $data->nim,
                    'department' => $data->department,
                    'faculty' => $data->faculty,
                    'university' => $data->university,
                ]
            );

            // 3. Update Foto Profil
            if ($data->profile_photo instanceof UploadedFile) {
                $oldAttachment = $user->attachments()->where('is_primary', true)->first();
                if ($oldAttachment) {
                    Storage::disk('public')->delete($oldAttachment->file_url);
                    $oldAttachment->delete();
                }

                $path = $data->profile_photo->store('profile_pictures', 'public');
                Attachment::create([
                    'attachable_type' => User::class,
                    'attachable_id' => $user->id,
                    'file_url' => $path,
                    'file_type' => $data->profile_photo->getClientMimeType(),
                    'is_primary' => true,
                    'uploaded_by' => auth()->id(),
                ]);
            }

            return $user;
        });
    }
}
