<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\Role as RoleEnum;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia, FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes, InteractsWithMedia;

    public function canAccessPanel(Panel $panel): bool
    {
        return
            $this->hasRole(RoleEnum::ADMIN->value) ||
            $this->hasRole(RoleEnum::SUPER_ADMIN->value) ||
            $this->hasRole(RoleEnum::HOSTESS->value);
    }

    /**
     * Override the roles relationship to use our custom Role model
     */
    public function roles(): MorphToMany
    {
        return $this->morphToMany(
            Role::class,
            'model',
            config('permission.table_names.model_has_roles'),
            config('permission.column_names.model_morph_key'),
            'role_id'
        );
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified_at',
        'new_email',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'verified_at' => 'datetime',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile();
    }
    public function getImageAttribute()
    {
        return $this->hasMedia('image') ? $this->getFirstMediaUrl('image') : null;
    }

    public function getVisitorTitleAttribute()
    {
        return __("panel/visitors.resource.single") . " - " . $this->name;
    }
    public function getExhibitorTitleAttribute()
    {
        return __("panel/exhibitors.resource.single") . " - " . $this->name;
    }
    public function getAdminTitleAttribute()
    {
        return __("panel/admins.resource.single") . " - " . $this->name;
    }

    /**
     * Get the events where this user is assigned as hostess.
     */
    public function assignedEvents(): BelongsToMany
    {
        return $this->belongsToMany(EventAnnouncement::class, 'event_announcement_user')
            ->withTimestamps();
    }

    /**
     * Get all badge check logs performed by this user.
     */
    public function performedCheckLogs()
    {
        return $this->hasMany(BadgeCheckLog::class, 'checked_by_user_id');
    }

    /**
     * Get all check-ins performed by this user.
     */
    public function performedCheckIns()
    {
        return $this->hasMany(CurrentAttendee::class, 'checked_in_by_user_id');
    }
}
