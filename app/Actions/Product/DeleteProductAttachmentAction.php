<?php

namespace App\Actions\Product;

use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

class DeleteProductAttachmentAction
{
    public function execute(Attachment $attachment): void
    {
        $product = $attachment->attachable;
        $wasPrimary = $attachment->is_primary;

        // Hapus file fisik spesifik
        if (Storage::disk('public')->exists($attachment->file_url)) {
            Storage::disk('public')->delete($attachment->file_url);
        }

        $attachment->delete();

        // Jika foto utama dihapus, lempar status "is_primary" ke foto pertama yang tersisa
        if ($wasPrimary && $product) {
            $nextPrimary = $product->attachments()->orderBy('sort_order')->first();
            if ($nextPrimary) {
                $nextPrimary->update(['is_primary' => true]);
            }
        }
    }
}
