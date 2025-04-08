<?php

namespace App\Observers;

use App\Activity\PlanTierActivity;
use App\Enums\LogEvent;
use App\Models\PlanTier;

class PlanTierObserver
{
    public function created(PlanTier $planTier)
    {
        // Log the creation using the activity class
        PlanTierActivity::logCreation($planTier);
    }

    public function updated(PlanTier $planTier)
    {
        $translatableFields = ['title'];
        $changes = [];

        foreach ($translatableFields as $field) {
            if ($planTier->isDirty($field)) {
                foreach (['fr', 'en', 'ar'] as $locale) {
                    $originalValue = $planTier->getOriginal($field) ? $planTier->getOriginal($field)[$locale] : null;
                    if ($planTier->getTranslation($field, $locale) !== $originalValue) {
                        $frenchFieldName = match ($field) {
                            'title' => 'titre'
                        };
                        $changes["$frenchFieldName $locale ancien"] = $originalValue;
                        $changes["$frenchFieldName $locale nouveau"] = $planTier->getTranslation($field, $locale);
                    }
                }
            }
        }

        PlanTierActivity::logUpdate($planTier, $changes);
    }

    public function deleted(PlanTier $planTier)
    {
        PlanTierActivity::logPlanTierAction($planTier, LogEvent::Deletion, "Suppression du niveau de plan");
    }

    public function forceDeleted(PlanTier $planTier)
    {
        PlanTierActivity::logPlanTierAction($planTier, LogEvent::ForceDeletion, "Suppression définitive du niveau de plan");
    }

    public function restored(PlanTier $planTier)
    {
        PlanTierActivity::logPlanTierAction($planTier, LogEvent::Restoration, "Restauration du niveau de plan");
    }
}
