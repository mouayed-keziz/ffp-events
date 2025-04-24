<?php

namespace App\Models;

use App\Enums\Role as RoleEnum;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'name' => RoleEnum::class,
    ];

    /**
     * Get a displayable label for the role.
     */
    public function getFormattedNameAttribute()
    {
        return $this->name instanceof RoleEnum ? $this->name->getLabel() : $this->name;
    }

    /**
     * Get the color for badge display.
     */
    public function getColorAttribute()
    {
        return $this->name instanceof RoleEnum ? $this->name->getColor() : 'gray';
    }

    /**
     * Get the icon for display.
     */
    public function getIconAttribute()
    {
        return $this->name instanceof RoleEnum ? $this->name->getIcon() : null;
    }
}
