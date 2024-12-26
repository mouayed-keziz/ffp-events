<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Enums\ArticleStatus;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use Spatie\Translatable\HasTranslations;

class Article extends Model implements HasMedia, Viewable
{
    use SoftDeletes, InteractsWithMedia, HasFactory, InteractsWithViews;
    use HasTranslations;

    protected $fillable = ['title', 'slug', 'description', 'content', 'published_at'];
    public $translatable = ['title', 'slug', 'description', 'content'];

    protected $with = ['categories'];

    protected $casts = [
        'published_at' => 'datetime',
        'status' => ArticleStatus::class,
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile();
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->whereNull('published_at');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published_at', '>', now());
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }


    public function getViewsAttribute()
    {
        return $this->views()->count();
    }
    public function getArticleTitleAttribute()
    {
        return __("panel/articles.resource.single") . " - " . $this->title;
    }
    public function getStatusAttribute(): ArticleStatus
    {
        if ($this->trashed()) {
            return ArticleStatus::Deleted;
        }

        if ($this->published_at === null) {
            return ArticleStatus::Draft;
        }

        if ($this->published_at > now()) {
            return ArticleStatus::Pending;
        }

        return ArticleStatus::Published;
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
