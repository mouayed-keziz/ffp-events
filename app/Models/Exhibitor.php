<?php

namespace App\Models;

use App\Enums\Currency;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Exhibitor extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, SoftDeletes, InteractsWithMedia;

    // Add fillable properties
    protected $fillable = [
        'name',
        'email',
        'password',
        'currency',
        'verified_at',
        'new_email'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'verified_at' => 'datetime',
        'currency' => Currency::class,
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile();
    }

    public function getImageAttribute()
    {
        return $this->hasMedia('image') ? $this->getFirstMediaUrl('image') : null;
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(ExhibitorSubmission::class);
    }
}
