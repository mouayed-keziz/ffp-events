<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Article extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;

    protected $fillable = [
        'title',
        'description',
        'content',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function scopeUnpublished(Builder $query): Builder
    {
        return $query->whereNull('published_at')->orWhere('published_at', '>', now());
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
