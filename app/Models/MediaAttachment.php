<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MediaAttachment extends Model
{
    protected $guarded = ['id'];

    public function attachable()
    {
        return $this->morphTo();
    }
}
