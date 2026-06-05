<?php

namespace App\Actions\RawMaterial;

use App\DTOs\RawMaterial\RestockMaterialData;
use App\Models\Attachment;
use App\Models\RawMaterial;
use App\Models\RawMaterialMovement;
use App\Models\Reimbursement;
use Illuminate\Support\Facades\DB;

class RestockMaterialAction
{
    /**
     * Atomic restock: Reimbursement → Attachment → Movement → Stock increment.
     *
     * The entire flow is wrapped in a DB transaction so if file storage or
     * any downstream insert fails, the whole purchase record is rolled back.
     */
    public function execute(RestockMaterialData $data): void
    {
        DB::transaction(function () use ($data) {
            // 1. Create the reimbursement record (financial audit trail)
            $reimbursement = Reimbursement::create([
                'user_id' => auth()->id(),
                'title' => $data->reimbursement_title,
                'total_amount' => $data->total_amount,
                'status' => 'pending',
            ]);

            // 2. Store file and create polymorphic attachment (receipt/transfer proof)
            $path = $data->payment_proof->store('reimbursements', 'public');

            Attachment::create([
                'attachable_type' => Reimbursement::class,
                'attachable_id' => $reimbursement->id,
                'file_url' => $path,
                'file_type' => $data->payment_proof->getClientMimeType(),
                'is_primary' => true,
                'uploaded_by' => auth()->id(),
            ]);

            // 3. Record the inbound movement linked to the reimbursement
            RawMaterialMovement::create([
                'raw_material_id' => $data->raw_material_id,
                'type' => 'in',
                'quantity' => $data->quantity,
                'reimbursement_id' => $reimbursement->id,
                'notes' => $data->notes,
                'created_by' => auth()->id(),
            ]);

            // 4. Increment the master stock balance
            RawMaterial::where('id', $data->raw_material_id)
                ->increment('current_stock', $data->quantity);
        });
    }
}
