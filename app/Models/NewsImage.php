<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsImage extends Model
{
    public $timestamps = false; // Karena di schema tidak ada timestamps
    protected $fillable = ['news_id', 'image_path', 'caption', 'is_primary', 'sort_order'];

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
