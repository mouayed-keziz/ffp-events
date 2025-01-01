<?php

namespace App\Models;

use App\Observers\CategoryObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = ['name', 'slug'];
    public $translatable = ['name'];

    // protected $casts = [
    //     'name' => 'array',
    //     'slug' => 'array',
    // ];

    public function getRecordTitleAttribute()
    {
        return __("panel/articles.categories.single") . " - " . $this->name;
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::observe(CategoryObserver::class);
    }
}
