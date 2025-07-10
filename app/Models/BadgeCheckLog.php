<?php

namespace App\Models;

use App\Enums\CheckInOutAction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class BadgeCheckLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_announcement_id',
        'badge_id',
        'checked_by_user_id',
        'action',
        'action_time',
        'note',
        'badge_code',
        'badge_name',
        'badge_email',
        'badge_position',
        'badge_company',
    ];

    protected $casts = [
        'action' => CheckInOutAction::class,
        'action_time' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the event announcement this log belongs to.
     */
    public function eventAnnouncement(): BelongsTo
    {
        return $this->belongsTo(EventAnnouncement::class);
    }

    /**
     * Get the badge this log is for.
     */
    public function badge(): BelongsTo
    {
        return $this->belongsTo(Badge::class);
    }

    /**
     * Get the user who performed the check-in/out action.
     */
    public function checkedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by_user_id');
    }

    // ==================== SCOPES ====================

    /**
     * Scope for check-in logs only.
     */
    public function scopeCheckIns(Builder $query): Builder
    {
        return $query->where('action', CheckInOutAction::CHECK_IN);
    }

    /**
     * Scope for check-out logs only.
     */
    public function scopeCheckOuts(Builder $query): Builder
    {
        return $query->where('action', CheckInOutAction::CHECK_OUT);
    }
}
