<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = false; // Schema hanya punya id, name, slug
    protected $fillable = ['name', 'slug'];

    public function news()
    {
        return $this->belongsToMany(News::class, 'news_tags');
    }
}
