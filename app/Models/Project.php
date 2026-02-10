<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations; // 1. Import Trait

class Project extends Model
{
    use HasTranslations; // 2. Gunakan Trait

    protected $guarded = ['id'];

    // 3. Definisikan kolom yang bisa diterjemahkan
    public $translatable = ['name', 'description'];

    protected $casts = [
        'deadline_global' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
