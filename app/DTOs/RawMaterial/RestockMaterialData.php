<?php

namespace App\DTOs\RawMaterial;

use Illuminate\Http\UploadedFile;

class RestockMaterialData
{
    public function __construct(
        public int $raw_material_id,
        public int $quantity,
        public int $total_amount,
        public string $reimbursement_title,
        public string $notes,
        public UploadedFile $payment_proof
    ) {}
}
