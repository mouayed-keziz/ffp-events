<?php

namespace App\Activity;

use App\Enums\LogEvent;
use App\Enums\LogName;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CategoryActivity
{
    /**
     * Log a category creation
     *
     * @param Category $category
     * @return void
     */
    public static function logCreation(Category $category): void
    {
        $properties = [];

        // Add translatable fields with flattened structure
        foreach (['fr', 'en', 'ar'] as $locale) {
            $properties["nom $locale"] = $category->getTranslation('name', $locale);
        }

        activity()
            ->useLog(LogName::Categories->value)
            ->event(LogEvent::Creation->value)
            ->performedOn($category)
            ->withProperties($properties)
            ->causedBy(self::getCurrentUser())
            ->log("Création d'une nouvelle catégorie");
    }

    /**
     * Log a category update
     *
     * @param Category $category
     * @param array $changes
     * @return void
     */
    public static function logUpdate(Category $category, array $changes): void
    {
        if (!empty($changes)) {
            activity()
                ->useLog(LogName::Categories->value)
                ->event(LogEvent::Modification->value)
                ->performedOn($category)
                ->withProperties($changes)
                ->causedBy(self::getCurrentUser())
                ->log("Modification du nom de la catégorie");
        }
    }

    /**
     * Log a category deletion
     *
     * @param Category $category
     * @return void
     */
    public static function logDeletion(Category $category): void
    {
        $properties = [];

        foreach (['fr', 'en', 'ar'] as $locale) {
            $properties["nom $locale"] = $category->getTranslation('name', $locale);
        }

        activity()
            ->useLog(LogName::Categories->value)
            ->event(LogEvent::Deletion->value)
            ->performedOn($category)
            ->causedBy(self::getCurrentUser())
            ->withProperties($properties)
            ->log("Suppression de la catégorie");
    }

    /**
     * Get logged in user from any guard (web, visitor, exhibitor)
     *
     * @return Model|null
     */
    private static function getCurrentUser(): ?Model
    {
        // Check all three guards in priority order
        foreach (['web', 'visitor', 'exhibitor'] as $guard) {
            if (Auth::guard($guard)->check()) {
                return Auth::guard($guard)->user();
            }
        }

        return null;
    }
}
