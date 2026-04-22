<?php

namespace App\Actions\RawMaterial;

use App\Models\RawMaterial;

class DeleteRawMaterialAction
{
    public function execute(RawMaterial $material): void
    {
        try {
            $material->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1451) {
                throw new \Exception(__('Cannot delete this material because it has movement history or is used in bookings.'));
            }
            throw $e;
        }
    }
}
