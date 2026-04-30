<?php

namespace App\Actions\Project;

use App\DTOs\Project\OpenSourceProjectData;
use App\Models\OpenSourceProject;
use App\Models\Attachment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateOpenSourceProjectAction
{
    public function execute(OpenSourceProjectData $data): OpenSourceProject
    {
        return DB::transaction(function () use ($data) {
            $project = OpenSourceProject::create([
                'user_id' => $data->user_id,
                'title' => $data->title,
                'category' => $data->category,
                'status' => $data->status,
                'validated_by' => $data->status !== 'pending' ? auth()->id() : null,
            ]);

            // Looping dan simpan file dengan nama khusus
            foreach ($data->new_files as $index => $file) {
                // Ekstrak ekstensi (contoh: png, jpg, pdf)
                $extension = $file->getClientOriginalExtension() ?: $file->guessExtension();

                // Format nama: judul-project-kodeunik.ekstensi
                $fileName = Str::slug($data->title) . '-' . Str::random(6) . '.' . $extension;

                // Simpan menggunakan storeAs() untuk menetapkan nama file
                $path = $file->storeAs('open_source_projects', $fileName, 'public');

                Attachment::create([
                    'attachable_type' => OpenSourceProject::class,
                    'attachable_id' => $project->id,
                    'file_url' => $path,
                    'file_type' => $file->getClientMimeType(),
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                    'uploaded_by' => auth()->id(),
                ]);
            }

            return $project;
        });
    }
}
