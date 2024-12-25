<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = ['name', 'slug'];
    public $translatable = ['name', 'slug'];

    // protected $casts = [
    //     'name' => 'array',
    //     'slug' => 'array',
    // ];

    public function getCategoryTitleAttribute()
    {
        return __("articles.categories.single") . " - " . $this->name;
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }
}
