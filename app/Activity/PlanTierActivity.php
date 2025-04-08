<?php

namespace App\Activity;

use App\Enums\LogEvent;
use App\Enums\LogName;
use App\Models\PlanTier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PlanTierActivity
{
    /**
     * Log a plan tier creation
     *
     * @param PlanTier $planTier
     * @return void
     */
    public static function logCreation(PlanTier $planTier): void
    {
        $properties = self::buildTranslationProperties($planTier);

        activity()
            ->useLog(LogName::PlanTiers->value)
            ->event(LogEvent::Creation->value)
            ->performedOn($planTier)
            ->withProperties($properties)
            ->causedBy(self::getCurrentUser())
            ->log("Création d'un nouveau niveau de plan");
    }

    /**
     * Log a plan tier update
     *
     * @param PlanTier $planTier
     * @param array $changes
     * @return void
     */
    public static function logUpdate(PlanTier $planTier, array $changes): void
    {
        if (!empty($changes)) {
            activity()
                ->useLog(LogName::PlanTiers->value)
                ->event(LogEvent::Modification->value)
                ->performedOn($planTier)
                ->withProperties($changes)
                ->causedBy(self::getCurrentUser())
                ->log("Modification du niveau de plan");
        }
    }

    /**
     * Log a plan tier action (delete, force delete, restore)
     *
     * @param PlanTier $planTier
     * @param LogEvent $event
     * @param string $description
     * @return void
     */
    public static function logPlanTierAction(PlanTier $planTier, LogEvent $event, string $description): void
    {
        $properties = self::buildSimpleProperties($planTier);

        activity()
            ->useLog(LogName::PlanTiers->value)
            ->event($event->value)
            ->performedOn($planTier)
            ->causedBy(self::getCurrentUser())
            ->withProperties($properties)
            ->log($description);
    }

    /**
     * Build complete properties array for a plan tier
     *
     * @param PlanTier $planTier
     * @return array
     */
    private static function buildTranslationProperties(PlanTier $planTier): array
    {
        $properties = [];

        // Add translatable fields
        foreach (['fr', 'en', 'ar'] as $locale) {
            $properties["titre $locale"] = $planTier->getTranslation('title', $locale);
        }

        return $properties;
    }

    /**
     * Build simplified properties array for deletion, restoration, etc.
     *
     * @param PlanTier $planTier
     * @return array
     */
    private static function buildSimpleProperties(PlanTier $planTier): array
    {
        $properties = [];

        foreach (['fr', 'en', 'ar'] as $locale) {
            $properties["titre $locale"] = $planTier->getTranslation('title', $locale);
        }

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
