<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    use HasFactory;

    // Menandakan primary key bukan 'id' dan tidak auto-increment
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'address',
        'nik',
        'nim',
        'department',
        'faculty',
        'university',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
