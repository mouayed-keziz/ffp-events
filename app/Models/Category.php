<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug'
    ];

    public function getCategoryTitleAttribute()
    {
        return __("articles.categories.single") . " - " . $this->name;
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }
}
