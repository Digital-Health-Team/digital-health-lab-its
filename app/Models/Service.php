<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordsActivity;

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
