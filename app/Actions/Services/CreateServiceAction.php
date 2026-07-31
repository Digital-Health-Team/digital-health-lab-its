<?php

namespace App\Actions\Services;

use App\DTOs\Service\ServiceData;
use App\Models\Service;

class CreateServiceAction
{
    public function execute(ServiceData $data): Service
    {
        return Service::create([
            'name' => $data->name,
            'service_type' => $data->service_type,
            'description' => $data->description,
            'base_price' => $data->base_price,
            'whatsapp_number' => $data->whatsapp_number,
        ]);
    }
}
