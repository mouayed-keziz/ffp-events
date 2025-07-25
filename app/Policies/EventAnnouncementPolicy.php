<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\EventAnnouncement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventAnnouncementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole([Role::SUPER_ADMIN->value, Role::ADMIN->value]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EventAnnouncement $eventAnnouncement): bool
    {
        return $user->hasRole([Role::SUPER_ADMIN->value, Role::ADMIN->value]);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(Role::SUPER_ADMIN->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EventAnnouncement $eventAnnouncement): bool
    {
        return $user->hasRole(Role::SUPER_ADMIN->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EventAnnouncement $eventAnnouncement): bool
    {
        return $user->hasRole(Role::SUPER_ADMIN->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, EventAnnouncement $eventAnnouncement): bool
    {
        return $user->hasRole(Role::SUPER_ADMIN->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, EventAnnouncement $eventAnnouncement): bool
    {
        return $user->hasRole(Role::SUPER_ADMIN->value);
    }
}
