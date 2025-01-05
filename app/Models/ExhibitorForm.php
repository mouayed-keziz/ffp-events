<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ExhibitorForm extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['title', 'event_announcement_id', 'fields'];
    protected $casts = ['fields' => 'array'];
    public $translatable = ["title"];

    // -------------------- Relationships --------------------
    public function eventAnnouncement()
    {
        return $this->belongsTo(EventAnnouncement::class);
    }
}
