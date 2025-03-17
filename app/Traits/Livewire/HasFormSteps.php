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
        $this->validate($validation['rules'], [], $validation['attributes']);
    }
}
