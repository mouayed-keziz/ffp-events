<?php

namespace App\Models;

use App\Observers\PlanTierObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class PlanTier extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title',
    ];

    public $translatable = ['title'];

    protected static function boot()
    {
        parent::boot();
        static::observe(PlanTierObserver::class);
    }

    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }
}
