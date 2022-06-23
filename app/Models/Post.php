<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'slug', 'cover_image'];

    public static function generateSlug($title)
    {
        return Str::slug($title, '-');
    }
}
