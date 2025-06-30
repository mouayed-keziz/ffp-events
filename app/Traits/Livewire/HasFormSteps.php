<?php

namespace App\Traits\Livewire;

use App\Forms\ExhibitorFormActions;

trait HasFormSteps
{
    /**
     * Move to the next step in the form
     */
    public function nextStep()
    {
        // Validate the current step before proceeding
        $this->validateCurrentStep();

        if ($this->currentStep < $this->totalSteps - 1) {
            $this->currentStep++;
        }

        // Calculate total price when moving to a new step
        $this->calculateTotalPrice();
    }

    /**
     * Move to the previous step in the form
     */
    public function previousStep()
    {
        if ($this->currentStep > 0) {
            $this->currentStep--;
        }

        // Recalculate price when navigating back
        $this->calculateTotalPrice();
    }

    /**
     * Validate the current form step
     */
    protected function validateCurrentStep()
    {
        $actions = new ExhibitorFormActions();
        $validation = $actions->getValidationRules($this->event, $this->currentStep);
        dd($validation['rules'], $validation['attributes']);
        $this->validate($validation['rules'], [], $validation['attributes']);

        // Additional terms validation for exhibit-event component on the first step
        // This validation only applies to the exhibit-event component and only when it's the first form step
        if (property_exists($this, 'terms_accepted') && $this->currentStep === 0) {
            $this->validate(
                [
                    'terms_accepted' => 'accepted',
                ],
                [],
                [
                    'terms_accepted' => __('website/visit-event.terms_and_conditions'),
                ]
            );
        }
    }
}
