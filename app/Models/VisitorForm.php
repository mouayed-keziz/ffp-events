<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_announcement_id',
        'fields',
    ];

    protected $casts = [
        'fields' => 'array',
    ];

    // -------------------- Relationships --------------------
    public function eventAnnouncement()
    {
        return $this->belongsTo(EventAnnouncement::class);
    }
}
