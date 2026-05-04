<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\RecordsActivity;

class RawMaterial extends Model
{
    use HasFactory, RecordsActivity;

    protected $fillable = [
        'name',
        'category',
        'unit',
        'current_stock',
    ];

    public function movements(): HasMany
    {
        return $this->hasMany(RawMaterialMovement::class);
    }
}
