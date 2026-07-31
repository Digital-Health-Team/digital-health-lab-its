<?php

namespace App\Actions\Services;

use App\Models\Service;

class DeleteServiceAction
{
    public function execute(Service $service): void
    {
        try {
            $service->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            // Error 1451 adalah constraint violation (data sedang dipakai di tabel lain)
            if ($e->errorInfo[1] == 1451) {
                throw new \Exception(__('Cannot delete this service because it is being used in existing bookings.'));
            }
            throw $e;
        }
    }
}
