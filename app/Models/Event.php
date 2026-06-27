<?php

namespace App\Models;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory, RecordsActivity;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'year',
        'theme_title',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }
}
