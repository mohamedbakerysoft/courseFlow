<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'content',
    ];

    public static function findBySlugOrFail(string $slug): self
    {
        return static::query()->where('slug', $slug)->firstOrFail();
    }
}
