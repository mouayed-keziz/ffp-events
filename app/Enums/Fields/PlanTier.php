<?php

namespace App\Enums\Fields;

use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Facades\App;

class PlanTier
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

        // Find the selected plan
        foreach ($answer['plans'] as $plan) {
            if (!empty($plan['selected']) && $plan['selected'] === true) {
                // Get price from plan details
                $price = floatval($plan['price'][$preferredCurrency] ?? 0);
                break;
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
     * Update plan tier selections based on the selected plan
     * 
     * @param array $plans Current plans array 
     * @param mixed $selectedPlanId ID of the plan to select
     * @return array Updated plans with selected state
     */
    public static function updatePlans(array $plans, $selectedPlanId): array
    {
        if (empty($plans)) {
            return [];
        }

        foreach ($plans as $index => $plan) {
            $isCurrentPlanSelected = $plan['plan_id'] == $selectedPlanId;
            $plans[$index]['selected'] = $isCurrentPlanSelected;
        }

        return $plans;
    }


    public static function createDisplayComponent(array $field, string $label, $answer)
    {
        return \App\Infolists\Components\PlantierEntry::make('ecommerce')
            ->label($label)
            ->state($answer);
    }
}
