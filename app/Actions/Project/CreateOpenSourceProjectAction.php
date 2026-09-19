<?php

namespace App\Actions\Project;

use App\DTOs\Project\OpenSourceProjectData;
use App\Models\Attachment;
use App\Models\OpenSourceProject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

class CreateOpenSourceProjectAction
{
    public function execute(OpenSourceProjectData $data): OpenSourceProject
    {
        return DB::transaction(function () use ($data) {
            $slug = $data->slug ?: $this->uniqueSlug($data->title);

            $project = OpenSourceProject::create([
                'user_id' => $data->user_id,
                'title' => $data->title,
                'slug' => $slug,
                'caption' => $data->caption,
                'category' => $data->category,
                'listing_type' => $data->listing_type,
                'status' => $data->status,
                'validated_by' => $data->status !== 'pending' ? auth()->id() : null,
                'description' => $data->description ?: null,
                'highlights' => $data->highlights ?: null,
                'cover_color' => $data->cover_color,
                'is_featured' => $data->is_featured,
                'license' => $data->license,
                'version' => $data->version,
                'format' => $data->format,
                'includes' => $data->includes ?: null,
                'title_en' => $data->title_en,
                'caption_en' => $data->caption_en,
                'description_en' => $data->description_en ?: null,
                'highlights_en' => $data->highlights_en ?: null,
                'includes_en' => $data->includes_en ?: null,
            ]);

            foreach ($data->new_files as $index => $file) {
                $extension = $file->getClientOriginalExtension() ?: $file->guessExtension();
                $fileName = Str::slug($data->title).'-'.Str::random(6).'.'.$extension;
                $path = $file->storeAs('open_source_projects', $fileName, 'public');

                Attachment::create([
                    'attachable_type' => OpenSourceProject::class,
                    'attachable_id' => $project->id,
                    'file_url' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => Number::fileSize($file->getSize()),
                    'file_type' => $file->getClientMimeType(),
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                    'uploaded_by' => auth()->id(),
                ]);
            }

            return $project;
        });
    }

    /** open_source_projects.slug is UNIQUE — two users submitting the same title would 500. */
    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (OpenSourceProject::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
