<?php

namespace App\Models;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Reimbursement extends Model
{
    use HasFactory, RecordsActivity;

    protected $guarded = ['id'];

    // ==========================================
    // ELOQUENT RELATIONSHIPS
    // ==========================================

    /** The user who submitted this reimbursement request. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Polymorphic: digital receipts, proof of transfer, etc.
     * Uses the existing attachments table via morphs('attachable').
     */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /** Material movements funded by this reimbursement (type='in'). */
    public function materialMovements(): HasMany
    {
        return $this->hasMany(RawMaterialMovement::class);
    }
}
