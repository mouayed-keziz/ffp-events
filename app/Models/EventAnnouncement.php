<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventAnnouncement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'content',
        'location',
        'status',
        'publish_at',
        'image_path',
        'start_date',
        'end_date',
        'max_exhibitors',
        'max_visitors',
        'is_featured'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'publish_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

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
