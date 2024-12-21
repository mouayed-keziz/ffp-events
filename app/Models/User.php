<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

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

    public function scopeVisitors($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->where('name', 'visitor');
        });
    }

    public function scopeExhibitors($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->where('name', 'exhibitor');
        });
    }
    public function scopeAdmins($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->where('name', 'admin');
        });
    }

    public function getVisitorTitleAttribute()
    {
        return __("visitors.resource.single") . " - {$this->name}";
    }
    public function getExhibitorTitleAttribute()
    {
        return __("exhibitors.resource.single") . " - {$this->name}";
    }
    public function getAdminTitleAttribute()
    {
        return __("admins.resource.single") . " - {$this->name}";
    }
}
