<?php

namespace App\Actions\Publication;

use App\DTOs\Publication\PublicationData;
use App\Models\Publication;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

class UpdatePublicationAction
{
    public function execute(Publication $publication, PublicationData $data): Publication
    {
        $slug = $data->slug ?: Str::slug($data->title);

        $thumbnailPath = $publication->thumbnail_path;
        if ($data->thumbnail_file) {
            if ($thumbnailPath && Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }
            $ext = $data->thumbnail_file->getClientOriginalExtension() ?: $data->thumbnail_file->guessExtension();
            $thumbnailPath = $data->thumbnail_file->storeAs(
                'publications/thumbnails',
                Str::slug($data->title).'-'.Str::random(6).'.'.$ext,
                'public'
            );
        }

        $pdfPath = $publication->pdf_path;
        $pdfFileSize = $data->pdf_file_size ?? $publication->pdf_file_size;
        if ($data->pdf_file) {
            if ($pdfPath && Storage::disk('public')->exists($pdfPath)) {
                Storage::disk('public')->delete($pdfPath);
            }
            $pdfPath = $data->pdf_file->storeAs(
                'publications/pdfs',
                Str::slug($data->title).'-'.Str::random(6).'.pdf',
                'public'
            );
            $pdfFileSize = Number::fileSize($data->pdf_file->getSize());
        }

        $publication->update([
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

        return $publication;
    }
}
