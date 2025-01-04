<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class EventAnnouncement extends Model implements HasMedia
{
    use HasFactory, SoftDeletes;
    use InteractsWithMedia, HasTranslations;

    protected $fillable = [
        'title',
        'description',
        'content',
        'terms',
        'location',
        'status',
        'publish_at',
        'start_date',
        'end_date',
        'max_exhibitors',
        'max_visitors',
        'is_featured'
    ];
    public $translatable = ['title', 'description', 'content', 'terms'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'publish_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile();
    }
    public function getImageAttribute()
    {
        $image = $this->getFirstMediaUrl('image');
        return $image ? $image : 'https://via.placeholder.com/150';
    }
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('publish_at', '<=', now());
    }

    public function scopeActive($query)
    {
        return $query->published()
            ->where('status', '!=', 'archived')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }
}
