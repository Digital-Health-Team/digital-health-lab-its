<?php

namespace App\Actions\Publication;

use App\Models\Publication;

class UpdatePublicationStatusAction
{
    public function execute(Publication $publication, string $status): void
    {
        // Whitelisted: Livewire methods are callable from the browser with arbitrary args.
        abort_unless(in_array($status, ['pending', 'approved', 'rejected'], true), 400);

        $publication->update([
            'status' => $status,
            'validated_by' => auth()->id(),
        ]);
    }
}
