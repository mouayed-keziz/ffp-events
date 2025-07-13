<?php

namespace App\Enums\Fields;

use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Facades\App;

class PlanTierCheckbox
{
    public static function initializeField(array $field): array
    {
        $fieldData = [
            'type' => $field['type'],
            'data' => [
                'label' => $field['data']['label'],
                'description' => $field['data']['description'] ?? null,
            ],
            'answer' => self::getDefaultAnswer($field)
        ];

        if (isset($field['data']['type'])) {
            $fieldData['data']['type'] = $field['data']['type'];
        }
        if (isset($field['data']['required'])) {
            $fieldData['data']['required'] = $field['data']['required'];
        }
        if (isset($field['data']['plan_tier_id'])) {
            $fieldData['data']['plan_tier_id'] = $field['data']['plan_tier_id'];

            // For plan tier, get associated plans
            $planTier = \App\Models\PlanTier::with('plans')->find($field['data']['plan_tier_id']);
            if ($planTier) {
                $fieldData['data']['plan_tier_details'] = [
                    'title' => $planTier->title,
                    'plans' => $planTier->plans->map(function ($plan) {
                        return [
                            'id' => $plan->id,
                            'title' => $plan->title,
                            'content' => $plan->content,
                            'price' => $plan->price,
                            'image' => $plan->image,
                        ];
                    })->toArray(),
                ];

                // Initialize the answer with all available plans (none selected)
                $fieldData['answer'] = [
                    'plans' => collect($fieldData['data']['plan_tier_details']['plans'] ?? [])->map(function ($plan) {
                        return [
                            'plan_id' => $plan['id'],
                            'selected' => false,
                            'price' => $plan['price'] ?? []
                        ];
                    })->toArray()
                ];
            }
        }

        return $fieldData;
    }

    public static function getDefaultAnswer(array $field = []): array
    {
        return [
            'plans' => []
        ];
    }

    public static function getValidationRules(array $field): array
    {
        $rules = [];

        // Check if field is required
        if (isset($field['data']['required']) && $field['data']['required']) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        return $rules;
    }

    public static function getInvoiceDetails(array $field, string $currency = 'DZD'): array
    {
        if (empty($field['answer']['plans'])) {
            return [];
        }

        $details = [];
        foreach ($field['answer']['plans'] as $plan) {
            if (!empty($plan['selected']) && $plan['selected'] === true) {
                $details[] = [
                    'title' => \App\Models\Plan::find($plan['plan_id'])?->getTranslation('title', 'fr') ?? '',
                    'quantity' => 1,
                    'price' => $plan['price'][$currency] ?? 0,

                ];
            }
        }
        return $details;
    }

    public static function processFieldAnswer($answer, array $fieldData = [])
    {
        if ($answer === null || (is_array($answer) && empty($answer))) {
            return $answer;
        }

        // Simplified structure - just return the plans array directly
        if (empty($answer['plans'])) {
            return ['plans' => []];
        }

        // Return the plans array as is
        return $answer;
    }

    public static function calculateFieldPrice($answer, array $fieldData, string $preferredCurrency): float
    {
        $price = 0;

        if (empty($answer) || empty($answer['plans'])) {
            return $price;
        }

        // Sum up prices for all selected plans
        foreach ($answer['plans'] as $plan) {
            if (!empty($plan['selected']) && $plan['selected'] === true) {
                // Get price from plan details
                $price += floatval($plan['price'][$preferredCurrency] ?? 0);
            }
        }

        return $price;
    }

    public static function isPriced(): bool
    {
        return true;
    }

    public static function needsQuantity(): bool
    {
        return false;
    }

    /**
     * Toggle plan selection for checkbox behavior
     * 
     * @param array $plans Current plans array 
     * @param mixed $planId ID of the plan to toggle
     * @return array Updated plans with toggled state
     */
    public static function togglePlanSelection(array $plans, $planId): array
    {
        if (empty($plans)) {
            return [];
        }

        foreach ($plans as $index => $plan) {
            if ($plan['plan_id'] == $planId) {
                // Toggle the selected state
                $plans[$index]['selected'] = !($plan['selected'] ?? false);
            }
        }

        return $plans;
    }

    public static function createDisplayComponent(array $field, string $label, $answer)
    {
        $locale = App::getLocale();

        // Create a Group with title-description entry and plantier checkbox component
        return \Filament\Infolists\Components\Group::make()
            ->schema([
                \App\Infolists\Components\TitleDescriptionEntry::make('heading')
                    ->state([
                        'title' => $label,
                        'description' => $field['data']['description'][$locale] ?? ($field['data']['description']['en'] ?? null),
                    ]),

                \App\Infolists\Components\PlanTierCheckboxEntry::make('plantier_checkbox')
                    ->label('')
                    ->state($answer)
                    ->extraAttributes(['class' => 'mt-3']),
            ]);
    }

    /**
     * Get label-answer pair for plan tier checkbox field
     *
     * @param array $field The field definition with type, data and answer
     * @param string $language Language code (default: 'fr')
     * @return array Array with 'label' and 'answer' keys
     */
    public static function getLabelAnswerPair(array $field, string $language = 'fr'): array
    {
        $label = $field['data']['label'][$language] ??
            $field['data']['label']['fr'] ??
            $field['data']['label']['en'] ??
            'Unknown Field';

        $selectedPlans = [];
        if (!empty($field['answer']['plans'])) {
            foreach ($field['answer']['plans'] as $plan) {
                if (!empty($plan['selected']) && $plan['selected'] === true) {
                    $planModel = \App\Models\Plan::find($plan['plan_id']);
                    if ($planModel) {
                        $planTitle = $planModel->getTranslation('title', $language) ??
                            $planModel->getTranslation('title', 'fr') ??
                            $planModel->getTranslation('title', 'en') ??
                            'Unknown Plan';
                        $selectedPlans[] = $planTitle;
                    }
                }
            }
        }

        $answer = implode(', ', $selectedPlans);

        return [
            'label' => $label,
            'answer' => $answer
        ];
    }
}
