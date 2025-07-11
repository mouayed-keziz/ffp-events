<?php

namespace App\Traits\Livewire;

use App\Actions\Forms\FormFieldActions;

trait HasFormFieldUpdates
{
    /**
     * Update plan selection for plan_tier field
     *
     * @param string $answerPath The path to the answer in formData
     * @param string|int $selectedPlanId The ID of the selected plan
     */
    public function updatePlanSelection($answerPath, $selectedPlanId)
    {
        $this->formData = FormFieldActions::updatePlanSelection($this->formData, $answerPath, $selectedPlanId);

        // Recalculate price after changing selection
        $this->calculateTotalPrice();
    }

    /**
     * Toggle plan selection for plan_tier_checkbox field
     *
     * @param string $answerPath The path to the answer in formData
     * @param string|int $planId The ID of the plan to toggle
     */
    public function togglePlanSelection($answerPath, $planId)
    {
        $this->formData = FormFieldActions::togglePlanSelection($this->formData, $answerPath, $planId);

        // Recalculate price after changing selection
        $this->calculateTotalPrice();
    }

    /**
     * Update product selection and quantity for ecommerce fields
     *
     * @param string $answerPath The path to the answer in formData
     * @param string|int $productId The ID of the product
     * @param int $quantity The quantity to set if selected, 0 if unselected
     */
    public function updateProductQuantity($answerPath, $productId, $quantity = 1)
    {
        $this->formData = FormFieldActions::updateProductQuantity($this->formData, $answerPath, $productId, $quantity);

        // Recalculate price after changing selection
        $this->calculateTotalPrice();
    }

    /**
     * Update radio selection for RADIO_PRICED field
     *
     * @param string $answerPath The path to the answer in formData
     * @param string $selectedValue The value of the selected option
     */
    public function updateRadioSelection($answerPath, $selectedValue)
    {
        $this->formData = FormFieldActions::updateOptionSelection($this->formData, $answerPath, $selectedValue);

        // Recalculate price after changing selection
        $this->calculateTotalPrice();
    }

    /**
     * Update select option for SELECT_PRICED field
     *
     * @param string $answerPath The path to the answer in formData
     * @param string $selectedValue The value of the selected option
     */
    public function updateSelectOption($answerPath, $selectedValue)
    {
        $this->formData = FormFieldActions::updateOptionSelection($this->formData, $answerPath, $selectedValue);

        // Dispatch a browser event to inform Alpine.js of the change
        $this->dispatch('selectedchanged', [
            'path' => $answerPath,
            'label' => $selectedValue,
        ]);

        // Recalculate price after changing selection
        $this->calculateTotalPrice();
    }

    /**
     * Update country selection for COUNTRY_SELECT field
     *
     * @param string $answerPath The path to the answer in formData
     * @param string $selectedCountryCode The country code of the selected country
     */
    public function updateCountrySelectOption($answerPath, $selectedCountryCode)
    {
        $this->formData = FormFieldActions::updateCountrySelection($this->formData, $answerPath, $selectedCountryCode);

        // Dispatch a browser event to inform Alpine.js of the change
        $this->dispatch('countryselected', [
            'path' => $answerPath,
            'countryCode' => $selectedCountryCode,
        ]);

        // No price calculation needed for country select as it's not priced
    }
}
