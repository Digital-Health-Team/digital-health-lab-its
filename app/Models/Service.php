<?php

namespace App\Models;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory, RecordsActivity;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'service_type',
        'description',
        'base_price',
        'whatsapp_number',
    ];
}
