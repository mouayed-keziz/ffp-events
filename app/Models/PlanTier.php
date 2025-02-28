<?php

namespace App\Models;

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

    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }
}
