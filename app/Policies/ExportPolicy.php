<?php

namespace App\Policies;

use App\Models\Export;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Enums\Role;

class ExportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return auth()->user()->hasRole(Role::SUPER_ADMIN->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Export $export): bool
    {
        return auth()->user()->hasRole(Role::SUPER_ADMIN->value);;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return auth()->user()->hasRole(Role::SUPER_ADMIN->value);;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Export $export): bool
    {
        return auth()->user()->hasRole(Role::SUPER_ADMIN->value);;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Export $export): bool
    {
        return auth()->user()->hasRole(Role::SUPER_ADMIN->value);;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Export $export): bool
    {
        return auth()->user()->hasRole(Role::SUPER_ADMIN->value);;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Export $export): bool
    {
        return auth()->user()->hasRole(Role::SUPER_ADMIN->value);;
    }

    /**
     * Determine whether the user can download the export.
     */
    public function download(User $user, Export $export): bool
    {
        return auth()->user()->hasRole(Role::SUPER_ADMIN->value);
    }
}
