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
}
