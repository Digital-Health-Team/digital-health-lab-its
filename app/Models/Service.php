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
        'description',
        'base_price',
    ];
}
