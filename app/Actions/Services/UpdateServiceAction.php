<?php

namespace App\Actions\Services;

use App\DTOs\Service\ServiceData;
use App\Models\Service;

class UpdateServiceAction
{
    public function execute(Service $service, ServiceData $data): Service
    {
        $service->update([
            'name' => $data->name,
            'description' => $data->description,
            'base_price' => $data->base_price,
        ]);

        return $service;
    }
}
