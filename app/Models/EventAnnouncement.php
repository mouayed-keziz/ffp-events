<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventAnnouncement extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'event_id',
        'title',
        'content',
        'status',
        'publish_at',
        'image_path',
        'is_pinned',
    ];

    protected $casts = [
        'publish_at' => 'datetime',
        'is_pinned' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('publish_at', '<=', now());
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }
}

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'status',
        'image_path',
        'max_exhibitors',
        'max_visitors',
        'is_featured'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_featured' => 'boolean',
        'max_exhibitors' => 'integer',
        'max_visitors' => 'integer'
    ];

    public function exhibitors()
    {
        return $this->hasMany(User::class)->where('role', 'exhibitor');
    }

    public function visitors()
    {
        return $this->hasMany(User::class)->where('role', 'visitor');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function getIsActiveAttribute()
    {
        return now()->between($this->start_date, $this->end_date);
    }
}
