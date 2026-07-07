<?php

namespace App\Models;

use App\Enums\ReportStatus;
use App\Enums\ReportType;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class IssueReport extends Model
{
    use HasFactory, RecordsActivity;

    protected $guarded = ['id'];

    protected $casts = [
        'type' => ReportType::class,
        'status' => ReportStatus::class,
        'resolved_at' => 'datetime',
    ];

    /** Admin who submitted the report. */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /** Warehouse admin who resolved/rejected the report. */
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /** The affected item: Tool, RawMaterial or Inventory — null for free-form reports. */
    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
