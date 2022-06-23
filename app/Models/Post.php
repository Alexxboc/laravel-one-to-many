<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'slug', 'cover_image'];

    public static function generateSlug($title)
    {
        return Str::slug($title, '-');
    }

    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
