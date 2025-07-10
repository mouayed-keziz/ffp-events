<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurrentAttendee extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_announcement_id',
        'badge_id',
        'checked_in_at',
        'checked_in_by_user_id',
        'badge_code',
        'badge_name',
        'badge_email',
        'badge_position',
        'badge_company',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the event announcement this attendee is in.
     */
    public function eventAnnouncement(): BelongsTo
    {
        return $this->belongsTo(EventAnnouncement::class);
    }

    /**
     * Get the badge for this attendee.
     */
    public function badge(): BelongsTo
    {
        return $this->belongsTo(Badge::class);
    }

    /**
     * Get the user who checked in this attendee.
     */
    public function checkedInByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by_user_id');
    }
}
