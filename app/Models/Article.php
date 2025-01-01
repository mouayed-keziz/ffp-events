<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Enums\ArticleStatus;
use App\Traits\HasRichMedia;
use Spatie\Translatable\HasTranslations;
use App\Observers\ArticleObserver;

class Article extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;
    use HasTranslations;
    // use HasRichMedia;

    // public static $richFields = ['content'];

    protected $fillable = ['title', 'slug', 'description', 'content', 'published_at'];
    // public $translatable = ['title', 'slug', 'description'];
    public $translatable = ['title', 'description', 'content'];

    protected $with = ['categories'];

    protected $casts = [
        'published_at' => 'datetime',
        'status' => ArticleStatus::class,
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(ArticleObserver::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile();
        $this->addMediaCollection('attachments');
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
        return 999;
    }
    public function getRecordTitleAttribute()
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
