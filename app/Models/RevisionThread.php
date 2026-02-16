<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RevisionThread extends Model
{
    protected $guarded = ['id'];

    public function attachments()
    {
        return $this->morphMany(MediaAttachment::class, 'attachable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobdesk()
    {
        return $this->belongsTo(Jobdesk::class);
    }
}
