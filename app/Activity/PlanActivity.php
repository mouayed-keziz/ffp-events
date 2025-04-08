<?php

namespace App\Activity;

use App\Enums\LogEvent;
use App\Enums\LogName;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PlanActivity
{
    /**
     * Log a plan creation
     *
     * @param Plan $plan
     * @return void
     */
    public static function logCreation(Plan $plan): void
    {
        $properties = self::buildTranslationProperties($plan);

        activity()
            ->useLog(LogName::Plans->value)
            ->event(LogEvent::Creation->value)
            ->performedOn($plan)
            ->withProperties($properties)
            ->causedBy(self::getCurrentUser())
            ->log("Création d'un nouveau plan");
    }

    /**
     * Log a plan update
     *
     * @param Plan $plan
     * @param array $changes
     * @return void
     */
    public static function logUpdate(Plan $plan, array $changes): void
    {
        if (!empty($changes)) {
            activity()
                ->useLog(LogName::Plans->value)
                ->event(LogEvent::Modification->value)
                ->performedOn($plan)
                ->withProperties($changes)
                ->causedBy(self::getCurrentUser())
                ->log("Modification du plan");
        }
    }

    /**
     * Log a plan action (delete, force delete, restore)
     *
     * @param Plan $plan
     * @param LogEvent $event
     * @param string $description
     * @return void
     */
    public static function logPlanAction(Plan $plan, LogEvent $event, string $description): void
    {
        $properties = self::buildSimpleProperties($plan);

        activity()
            ->useLog(LogName::Plans->value)
            ->event($event->value)
            ->performedOn($plan)
            ->causedBy(self::getCurrentUser())
            ->withProperties($properties)
            ->log($description);
    }

    /**
     * Build complete properties array for a plan
     *
     * @param Plan $plan
     * @return array
     */
    private static function buildTranslationProperties(Plan $plan): array
    {
        $properties = [];

        // Add translatable fields
        foreach (['fr', 'en', 'ar'] as $locale) {
            $properties["titre $locale"] = $plan->getTranslation('title', $locale);
            $properties["contenu $locale"] = $plan->getTranslation('content', $locale);
        }

        // Add non-translatable fields
        $properties["plan_tier_id"] = $plan->plan_tier_id;

        // Handle price array with > notation
        if (is_array($plan->price)) {
            foreach ($plan->price as $key => $value) {
                $properties["prix > $key"] = $value;
            }
        }

        return $properties;
    }

    /**
     * Build simplified properties array for deletion, restoration, etc.
     *
     * @param Plan $plan
     * @return array
     */
    private static function buildSimpleProperties(Plan $plan): array
    {
        $properties = [];

        foreach (['fr', 'en', 'ar'] as $locale) {
            $properties["titre $locale"] = $plan->getTranslation('title', $locale);
        }

        $properties["plan_tier_id"] = $plan->plan_tier_id;

        return $properties;
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
