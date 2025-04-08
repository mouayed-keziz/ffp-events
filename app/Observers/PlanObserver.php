<?php

namespace App\Observers;

use App\Activity\PlanActivity;
use App\Enums\LogEvent;
use App\Models\Plan;

class PlanObserver
{
    public function created(Plan $plan)
    {
        // Log the creation using the activity class
        PlanActivity::logCreation($plan);
    }

    public function updated(Plan $plan)
    {
        $translatableFields = ['title', 'content'];
        $changes = [];

        foreach ($translatableFields as $field) {
            if ($plan->isDirty($field)) {
                foreach (['fr', 'en', 'ar'] as $locale) {
                    $originalValue = $plan->getOriginal($field) ? $plan->getOriginal($field)[$locale] : null;
                    if ($plan->getTranslation($field, $locale) !== $originalValue) {
                        $frenchFieldName = match ($field) {
                            'title' => 'titre',
                            'content' => 'contenu'
                        };
                        $changes["$frenchFieldName $locale ancien"] = $originalValue;
                        $changes["$frenchFieldName $locale nouveau"] = $plan->getTranslation($field, $locale);
                    }
                }
            }
        }

        // Handle non-translatable fields
        $regularFields = [
            'plan_tier_id' => 'niveau de plan'
        ];

        foreach ($regularFields as $field => $frenchName) {
            if ($plan->isDirty($field)) {
                $originalValue = $plan->getOriginal($field);
                $newValue = $plan->$field;

                $changes["$frenchName ancien"] = $originalValue;
                $changes["$frenchName nouveau"] = $newValue;
            }
        }

        // Handle the price array field
        if ($plan->isDirty('price')) {
            $originalPrice = $plan->getOriginal('price');
            $newPrice = $plan->price;

            if (is_array($originalPrice) && is_array($newPrice)) {
                foreach ($newPrice as $currency => $value) {
                    $originalCurrencyValue = $originalPrice[$currency] ?? null;
                    if ($value !== $originalCurrencyValue) {
                        $changes["prix $currency ancien"] = $originalCurrencyValue;
                        $changes["prix $currency nouveau"] = $value;
                    }
                }

                // Check for removed currencies
                foreach ($originalPrice as $currency => $value) {
                    if (!isset($newPrice[$currency])) {
                        $changes["prix $currency ancien"] = $value;
                        $changes["prix $currency nouveau"] = null;
                    }
                }
            }
        }

        PlanActivity::logUpdate($plan, $changes);
    }

    public function deleted(Plan $plan)
    {
        PlanActivity::logPlanAction($plan, LogEvent::Deletion, "Suppression du plan");
    }

    public function forceDeleted(Plan $plan)
    {
        PlanActivity::logPlanAction($plan, LogEvent::ForceDeletion, "Suppression définitive du plan");
    }

    public function restored(Plan $plan)
    {
        PlanActivity::logPlanAction($plan, LogEvent::Restoration, "Restauration du plan");
    }
}
