<?php

namespace App\Models;

use App\Enums\AttendeeStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'status',
        'total_time_spent_inside',
        'last_check_in_at',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'last_check_in_at' => 'datetime',
        'status' => AttendeeStatus::class,
        'total_time_spent_inside' => 'integer', // in minutes
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

    // ==================== ATTRIBUTES ====================

    /**
     * Get the total time spent inside including current session if inside.
     * Returns time in minutes.
     */
    protected function totalTimeSpentWithCurrent(): Attribute
    {
        return Attribute::make(
            get: function () {
                $totalTime = $this->total_time_spent_inside ?? 0;

                // If currently inside, add time from last check-in till now
                if ($this->status === AttendeeStatus::INSIDE && $this->last_check_in_at) {
                    $currentSessionTime = now()->diffInMinutes($this->last_check_in_at);
                    $totalTime += $currentSessionTime;
                }

                return $totalTime;
            }
        );
    }

    /**
     * Get formatted total time spent (with current session if inside).
     */
    protected function formattedTotalTimeSpent(): Attribute
    {
        return Attribute::make(
            get: function () {
                $totalMinutes = $this->total_time_spent_with_current;
                $hours = intval($totalMinutes / 60);
                $minutes = $totalMinutes % 60;

                if ($hours > 0) {
                    return "{$hours}h {$minutes}m";
                }

                return "{$minutes}m";
            }
        );
    }

    // ==================== METHODS ====================

    /**
     * Calculate and update total time spent when checking out.
     */
    public function updateTimeSpentOnCheckout(): void
    {
        if ($this->status === AttendeeStatus::INSIDE && $this->last_check_in_at) {
            $sessionTime = now()->diffInMinutes($this->last_check_in_at);
            $this->total_time_spent_inside = ($this->total_time_spent_inside ?? 0) + $sessionTime;
        }
    }
}
