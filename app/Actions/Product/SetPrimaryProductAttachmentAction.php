<?php

namespace App\Actions\Product;

use App\Models\Attachment;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SetPrimaryProductAttachmentAction
{
    public function execute(Attachment $attachment): void
    {
        DB::transaction(function () use ($attachment) {
            // Matikan is_primary pada semua attachment milik produk ini
            Attachment::where('attachable_type', Product::class)
                ->where('attachable_id', $attachment->attachable_id)
                ->update(['is_primary' => false]);

            // Jadikan attachment yang dipilih sebagai primary
            $attachment->update(['is_primary' => true]);
        });
    }
}
