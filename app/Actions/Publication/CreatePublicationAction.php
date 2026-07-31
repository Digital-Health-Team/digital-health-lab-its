<?php

namespace App\Actions\Publication;

use App\DTOs\Publication\PublicationData;
use App\Models\Publication;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

class CreatePublicationAction
{
    public function execute(PublicationData $data): Publication
    {
        $slug = $data->slug ?: $this->uniqueSlug($data->title);

        $thumbnailPath = null;
        if ($data->thumbnail_file) {
            $ext = $data->thumbnail_file->getClientOriginalExtension() ?: $data->thumbnail_file->guessExtension();
            $thumbnailPath = $data->thumbnail_file->storeAs(
                'publications/thumbnails',
                Str::slug($data->title).'-'.Str::random(6).'.'.$ext,
                'public'
            );
        }

        $pdfPath = null;
        $pdfFileSize = $data->pdf_file_size;
        if ($data->pdf_file) {
            $pdfPath = $data->pdf_file->storeAs(
                'publications/pdfs',
                Str::slug($data->title).'-'.Str::random(6).'.pdf',
                'public'
            );
            $pdfFileSize = Number::fileSize($data->pdf_file->getSize());
        }

        return Publication::create([
            'title' => $data->title,
            'slug' => $slug,
            'author' => $data->author,
            'category' => $data->category,
            'abstract' => $data->abstract,
            'description' => $data->description ?: null,
            'keywords' => $data->keywords ?: null,
            'title_en' => $data->title_en,
            'abstract_en' => $data->abstract_en,
            'description_en' => $data->description_en ?: null,
            'keywords_en' => $data->keywords_en ?: null,
            'doi' => $data->doi,
            'journal' => $data->journal,
            'pmid' => $data->pmid,
            'thumbnail_path' => $thumbnailPath,
            'pdf_path' => $pdfPath,
            'pdf_file_size' => $pdfFileSize,
            'is_free_access' => $data->is_free_access,
            'is_featured' => $data->is_featured,
            'published_at' => $data->published_at,
        ]);
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (Publication::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
