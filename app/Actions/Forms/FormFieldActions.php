<?php

namespace App\Actions\Forms;

use App\Enums\FormField;
use Illuminate\Support\Arr;

class FormFieldActions
{
    /**
     * Update select or radio option selection
     *
     * @param array $formData Current form data
     * @param string $answerPath Path to the answer field (e.g., "0.sections.1.fields.2.answer")
     * @param string $selectedValue Value to be selected
     * @return array Updated form data
     */
    public static function updateOptionSelection(array $formData, string $answerPath, $selectedValue): array
    {
        // Get field type from the path
        $fieldTypePath = str_replace('.answer', '.type', $answerPath);
        $fieldType = Arr::get($formData, $fieldTypePath);

        if (empty($fieldType)) {
            return $formData;
        }

        // Get the field options
        $optionsPath = $answerPath . '.options';
        $options = Arr::get($formData, $optionsPath, []);

        // Get the FormField enum from string
        $fieldTypeEnum = FormField::tryFrom($fieldType);

        if (!$fieldTypeEnum) {
            return $formData;
        }

        // Update options using the FormField enum's updateOptions method
        $updatedOptions = $fieldTypeEnum->updateOptions($options, $selectedValue);

        // Set the updated options back to the form data
        Arr::set($formData, $optionsPath, $updatedOptions);

        return $formData;
    }

    /**
     * Update plan selection for plan tier fields
     *
     * @param array $formData Current form data
     * @param string $answerPath Path to the answer field
     * @param mixed $selectedPlanId ID of the plan to select
     * @return array Updated form data
     */
    public static function updatePlanSelection(array $formData, string $answerPath, $selectedPlanId): array
    {
        // Get field type from the path
        $fieldTypePath = str_replace('.answer', '.type', $answerPath);
        $fieldType = Arr::get($formData, $fieldTypePath);

        if ($fieldType !== FormField::PLAN_TIER->value) {
            return $formData;
        }

        // Get the plans array
        $plansPath = $answerPath . '.plans';
        $plans = Arr::get($formData, $plansPath, []);

        // Update plans using PlanTier::updatePlans
        $updatedPlans = FormField::PLAN_TIER->updateOptions($plans, $selectedPlanId);

        // Set the updated plans back to the form data
        Arr::set($formData, $plansPath, $updatedPlans);

        return $formData;
    }

    /**
     * Toggle plan selection for plan tier checkbox fields
     *
     * @param array $formData Current form data
     * @param string $answerPath Path to the answer field
     * @param mixed $planId ID of the plan to toggle
     * @return array Updated form data
     */
    public static function togglePlanSelection(array $formData, string $answerPath, $planId): array
    {
        // Get field type from the path
        $fieldTypePath = str_replace('.answer', '.type', $answerPath);
        $fieldType = Arr::get($formData, $fieldTypePath);

        if ($fieldType !== FormField::PLAN_TIER_CHECKBOX->value) {
            return $formData;
        }

        // Get the plans array
        $plansPath = $answerPath . '.plans';
        $plans = Arr::get($formData, $plansPath, []);

        // Update plans using PlanTierCheckbox::togglePlanSelection
        $updatedPlans = FormField::PLAN_TIER_CHECKBOX->updateOptions($plans, $planId);

        // Set the updated plans back to the form data
        Arr::set($formData, $plansPath, $updatedPlans);

        return $formData;
    }

    /**
     * Update product selection and quantity for ecommerce fields
     *
     * @param array $formData Current form data
     * @param string $answerPath Path to the answer field
     * @param mixed $productId ID of the product to update
     * @param int|null $quantity Quantity to set (null means toggle selection)
     * @return array Updated form data
     */
    public static function updateProductQuantity(array $formData, string $answerPath, $productId, ?int $quantity = null): array
    {
        // Get field type from the path
        $fieldTypePath = str_replace('.answer', '.type', $answerPath);
        $fieldType = Arr::get($formData, $fieldTypePath);

        if ($fieldType !== FormField::ECOMMERCE->value) {
            return $formData;
        }

        // Get the products array
        $productsPath = $answerPath . '.products';
        $products = Arr::get($formData, $productsPath, []);

        // Determine the product data to pass to updateProducts
        $productData = $quantity !== null ? [$productId, $quantity] : $productId;

        // Update products using Ecommerce::updateProducts
        $updatedProducts = FormField::ECOMMERCE->updateOptions($products, $productData);

        // Set the updated products back to the form data
        Arr::set($formData, $productsPath, $updatedProducts);

        return $formData;
    }

    /**
     * Generic handler for updating form field selections
     *
     * @param array $formData Current form data 
     * @param string $fieldPath Path to the field including form index, section index, and field index
     * @param string $type Type of update: 'select', 'radio', 'plan', 'product'
     * @param mixed $value Value to set (meaning varies by update type)
     * @param mixed $extraValue Additional value needed for some update types (e.g., quantity)
     * @return array Updated form data
     */
    public static function updateField(array $formData, string $fieldPath, string $type, $value, $extraValue = null): array
    {
        $answerPath = "{$fieldPath}.answer";

        switch ($type) {
            case 'select':
            case 'radio':
                return self::updateOptionSelection($formData, $answerPath, $value);

            case 'plan':
                return self::updatePlanSelection($formData, $answerPath, $value);

            case 'product':
                return self::updateProductQuantity($formData, $answerPath, $value, $extraValue);

            default:
                return $formData;
        }
    }
}
