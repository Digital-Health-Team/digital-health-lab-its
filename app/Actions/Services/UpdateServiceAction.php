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
            'name_en' => $data->name_en,
            'service_type' => $data->service_type,
            'description' => $data->description,
            'description_en' => $data->description_en,
            'base_price' => $data->base_price,
            'whatsapp_number' => $data->whatsapp_number,
        ]);

        return $service;
    }
}
