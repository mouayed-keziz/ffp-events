<?php

namespace App\Enums;

use App\Enums\Fields\Checkbox;
use App\Enums\Fields\CheckboxPriced;
use App\Enums\Fields\CountrySelect;
use App\Enums\Fields\Ecommerce;
use App\Enums\Fields\Ecommerce2;
use App\Enums\Fields\Input;
use App\Enums\Fields\PlanTier;
use App\Enums\Fields\PlanTierCheckbox;
use App\Enums\Fields\Radio;
use App\Enums\Fields\RadioPriced;
use App\Enums\Fields\Select;
use App\Enums\Fields\SelectPriced;
use App\Enums\Fields\Upload;
use Filament\Support\Contracts\HasLabel;

enum FormField: string implements HasLabel
{
    case INPUT = "input";
    case SELECT = "select";
    case CHECKBOX = "checkbox";
    case RADIO = "radio";
    case UPLOAD = "upload";
    case SELECT_PRICED = "select_priced";
    case CHECKBOX_PRICED = "checkbox_priced";
    case RADIO_PRICED = "radio_priced";
    case ECOMMERCE = "ecommerce";
    case ECOMMERCE_2 = "ecommerce_2";
    case PLAN_TIER = "plan_tier";
    case PLAN_TIER_CHECKBOX = "plan_tier_checkbox";
    case COUNTRY_SELECT = "country_select";

    public function getLabel(): ?string
    {
        return trans('panel/forms.form_fields.' . $this->value);
    }

    public function getIcon(): ?string
    {
        $icons = [
            self::INPUT->value           => 'heroicon-o-pencil',
            self::SELECT->value          => 'heroicon-o-selector',
            self::CHECKBOX->value        => 'heroicon-o-check',
            self::RADIO->value           => 'heroicon-o-dot-circle',
            self::UPLOAD->value          => 'heroicon-o-cloud-upload',
            self::SELECT_PRICED->value   => 'heroicon-o-currency-dollar',
            self::CHECKBOX_PRICED->value => 'heroicon-o-clipboard-check',
            self::RADIO_PRICED->value    => 'heroicon-o-document-text',
            self::ECOMMERCE->value       => 'heroicon-o-shopping-cart',
            self::ECOMMERCE_2->value     => 'heroicon-o-shopping-cart',
            self::PLAN_TIER->value       => 'heroicon-o-table-cells',
            self::PLAN_TIER_CHECKBOX->value => 'heroicon-o-table-cells',
            self::COUNTRY_SELECT->value  => 'heroicon-o-globe-alt',
        ];

        return $icons[$this->value] ?? 'heroicon-o-question-mark';
    }

    public function getInvoiceDetails(array $field): array
    {
        return match ($this) {
            self::INPUT => Input::getInvoiceDetails($field),
            self::SELECT => Select::getInvoiceDetails($field),
            self::CHECKBOX => Checkbox::getInvoiceDetails($field),
            self::RADIO => Radio::getInvoiceDetails($field),
            self::UPLOAD => Upload::getInvoiceDetails($field),
            self::SELECT_PRICED => SelectPriced::getInvoiceDetails($field),
            self::CHECKBOX_PRICED => CheckboxPriced::getInvoiceDetails($field),
            self::RADIO_PRICED => RadioPriced::getInvoiceDetails($field),
            self::ECOMMERCE => Ecommerce::getInvoiceDetails($field),
            self::ECOMMERCE_2 => Ecommerce2::getInvoiceDetails($field),
            self::PLAN_TIER => PlanTier::getInvoiceDetails($field),
            self::PLAN_TIER_CHECKBOX => PlanTierCheckbox::getInvoiceDetails($field),
            self::COUNTRY_SELECT => CountrySelect::getInvoiceDetails($field),
        };
    }

    /**
     * Initialize a field structure based on its type
     */
    public function initializeField(array $field): array
    {
        return match ($this) {
            self::INPUT => Input::initializeField($field),
            self::SELECT => Select::initializeField($field),
            self::CHECKBOX => Checkbox::initializeField($field),
            self::RADIO => Radio::initializeField($field),
            self::UPLOAD => Upload::initializeField($field),
            self::SELECT_PRICED => SelectPriced::initializeField($field),
            self::CHECKBOX_PRICED => CheckboxPriced::initializeField($field),
            self::RADIO_PRICED => RadioPriced::initializeField($field),
            self::ECOMMERCE => Ecommerce::initializeField($field),
            self::ECOMMERCE_2 => Ecommerce2::initializeField($field),
            self::PLAN_TIER => PlanTier::initializeField($field),
            self::PLAN_TIER_CHECKBOX => PlanTierCheckbox::initializeField($field),
            self::COUNTRY_SELECT => CountrySelect::initializeField($field),
        };
    }

    /**
     * Get default answer for this field type
     */
    public function getDefaultAnswer(array $field = [])
    {
        return match ($this) {
            self::INPUT => Input::getDefaultAnswer($field),
            self::SELECT => Select::getDefaultAnswer($field),
            self::CHECKBOX => Checkbox::getDefaultAnswer($field),
            self::RADIO => Radio::getDefaultAnswer($field),
            self::UPLOAD => Upload::getDefaultAnswer($field),
            self::SELECT_PRICED => SelectPriced::getDefaultAnswer($field),
            self::CHECKBOX_PRICED => CheckboxPriced::getDefaultAnswer($field),
            self::RADIO_PRICED => RadioPriced::getDefaultAnswer($field),
            self::ECOMMERCE => Ecommerce::getDefaultAnswer($field),
            self::ECOMMERCE_2 => Ecommerce2::getDefaultAnswer($field),
            self::PLAN_TIER => PlanTier::getDefaultAnswer($field),
            self::PLAN_TIER_CHECKBOX => PlanTierCheckbox::getDefaultAnswer($field),
            self::COUNTRY_SELECT => CountrySelect::getDefaultAnswer($field),
        };
    }

    /**
     * Get validation rules for this field type
     */
    public function getValidationRules(array $field): array
    {
        return match ($this) {
            self::INPUT => Input::getValidationRules($field),
            self::SELECT => Select::getValidationRules($field),
            self::CHECKBOX => Checkbox::getValidationRules($field),
            self::RADIO => Radio::getValidationRules($field),
            self::UPLOAD => Upload::getValidationRules($field),
            self::SELECT_PRICED => SelectPriced::getValidationRules($field),
            self::CHECKBOX_PRICED => CheckboxPriced::getValidationRules($field),
            self::RADIO_PRICED => RadioPriced::getValidationRules($field),
            self::ECOMMERCE => Ecommerce::getValidationRules($field),
            self::ECOMMERCE_2 => Ecommerce2::getValidationRules($field),
            self::PLAN_TIER => PlanTier::getValidationRules($field),
            self::PLAN_TIER_CHECKBOX => PlanTierCheckbox::getValidationRules($field),
            self::COUNTRY_SELECT => CountrySelect::getValidationRules($field),
        };
    }

    /**
     * Process field answer for submission
     */
    public function processFieldAnswer($answer, array $fieldData = [])
    {
        return match ($this) {
            self::INPUT => Input::processFieldAnswer($answer, $fieldData),
            self::SELECT => Select::processFieldAnswer($answer, $fieldData),
            self::CHECKBOX => Checkbox::processFieldAnswer($answer, $fieldData),
            self::RADIO => Radio::processFieldAnswer($answer, $fieldData),
            self::UPLOAD => Upload::processFieldAnswer($answer, $fieldData),
            self::SELECT_PRICED => SelectPriced::processFieldAnswer($answer, $fieldData),
            self::CHECKBOX_PRICED => CheckboxPriced::processFieldAnswer($answer, $fieldData),
            self::RADIO_PRICED => RadioPriced::processFieldAnswer($answer, $fieldData),
            self::ECOMMERCE => Ecommerce::processFieldAnswer($answer, $fieldData),
            self::ECOMMERCE_2 => Ecommerce2::processFieldAnswer($answer, $fieldData),
            self::PLAN_TIER => PlanTier::processFieldAnswer($answer, $fieldData),
            self::PLAN_TIER_CHECKBOX => PlanTierCheckbox::processFieldAnswer($answer, $fieldData),
            self::COUNTRY_SELECT => CountrySelect::processFieldAnswer($answer, $fieldData),
        };
    }

    /**
     * Calculate price for this field based on the answers and preferred currency
     */
    public function calculateFieldPrice($answer, array $fieldData, string $preferredCurrency): float
    {
        return match ($this) {
            self::INPUT => Input::calculateFieldPrice($answer, $fieldData, $preferredCurrency),
            self::SELECT => Select::calculateFieldPrice($answer, $fieldData, $preferredCurrency),
            self::CHECKBOX => Checkbox::calculateFieldPrice($answer, $fieldData, $preferredCurrency),
            self::RADIO => Radio::calculateFieldPrice($answer, $fieldData, $preferredCurrency),
            self::UPLOAD => Upload::calculateFieldPrice($answer, $fieldData, $preferredCurrency),
            self::SELECT_PRICED => SelectPriced::calculateFieldPrice($answer, $fieldData, $preferredCurrency),
            self::CHECKBOX_PRICED => CheckboxPriced::calculateFieldPrice($answer, $fieldData, $preferredCurrency),
            self::RADIO_PRICED => RadioPriced::calculateFieldPrice($answer, $fieldData, $preferredCurrency),
            self::ECOMMERCE => Ecommerce::calculateFieldPrice($answer, $fieldData, $preferredCurrency),
            self::ECOMMERCE_2 => Ecommerce2::calculateFieldPrice($answer, $fieldData, $preferredCurrency),
            self::PLAN_TIER => PlanTier::calculateFieldPrice($answer, $fieldData, $preferredCurrency),
            self::PLAN_TIER_CHECKBOX => PlanTierCheckbox::calculateFieldPrice($answer, $fieldData, $preferredCurrency),
            self::COUNTRY_SELECT => CountrySelect::calculateFieldPrice($answer, $fieldData, $preferredCurrency),
        };
    }

    /**
     * Check if this field type is priced (has monetary value)
     */
    public function isPriced(): bool
    {
        return match ($this) {
            self::INPUT => Input::isPriced(),
            self::SELECT => Select::isPriced(),
            self::CHECKBOX => Checkbox::isPriced(),
            self::RADIO => Radio::isPriced(),
            self::UPLOAD => Upload::isPriced(),
            self::SELECT_PRICED => SelectPriced::isPriced(),
            self::CHECKBOX_PRICED => CheckboxPriced::isPriced(),
            self::RADIO_PRICED => RadioPriced::isPriced(),
            self::ECOMMERCE => Ecommerce::isPriced(),
            self::ECOMMERCE_2 => Ecommerce2::isPriced(),
            self::PLAN_TIER => PlanTier::isPriced(),
            self::PLAN_TIER_CHECKBOX => PlanTierCheckbox::isPriced(),
            self::COUNTRY_SELECT => CountrySelect::isPriced(),
        };
    }

    /**
     * Check if this field type needs quantity field
     */
    public function needsQuantity(): bool
    {
        return match ($this) {
            self::INPUT => Input::needsQuantity(),
            self::SELECT => Select::needsQuantity(),
            self::CHECKBOX => Checkbox::needsQuantity(),
            self::RADIO => Radio::needsQuantity(),
            self::UPLOAD => Upload::needsQuantity(),
            self::SELECT_PRICED => SelectPriced::needsQuantity(),
            self::CHECKBOX_PRICED => CheckboxPriced::needsQuantity(),
            self::RADIO_PRICED => RadioPriced::needsQuantity(),
            self::ECOMMERCE => Ecommerce::needsQuantity(),
            self::ECOMMERCE_2 => Ecommerce2::needsQuantity(),
            self::PLAN_TIER => PlanTier::needsQuantity(),
            self::PLAN_TIER_CHECKBOX => PlanTierCheckbox::needsQuantity(),
            self::COUNTRY_SELECT => CountrySelect::needsQuantity(),
        };
    }

    /**
     * Create display component for visitor submission
     * 
     * @param array $field The field definition with type, data and answer
     * @param string $label The field label
     * @param mixed $answer The field answer value
     * @param mixed|null $visitorSubmission Optional visitor submission model for file handling
     * @return mixed Component suitable for displaying in an Infolist
     */
    public function createDisplayComponent(array $field, string $label, $answer, $submission = null)
    {
        return match ($this) {
            self::INPUT => Input::createDisplayComponent($field, $label, $answer),
            self::SELECT => Select::createDisplayComponent($field, $label, $answer),
            self::CHECKBOX => Checkbox::createDisplayComponent($field, $label, $answer),
            self::RADIO => Radio::createDisplayComponent($field, $label, $answer),
            self::UPLOAD => Upload::createDisplayComponent($field, $label, $answer, $submission),
            self::SELECT_PRICED => SelectPriced::createDisplayComponent($field, $label, $answer),
            self::CHECKBOX_PRICED => CheckboxPriced::createDisplayComponent($field, $label, $answer),
            self::RADIO_PRICED => RadioPriced::createDisplayComponent($field, $label, $answer),
            self::ECOMMERCE => Ecommerce::createDisplayComponent($field, $label, $answer),
            self::ECOMMERCE_2 => Ecommerce2::createDisplayComponent($field, $label, $answer),
            self::PLAN_TIER => PlanTier::createDisplayComponent($field, $label, $answer),
            self::PLAN_TIER_CHECKBOX => PlanTierCheckbox::createDisplayComponent($field, $label, $answer),
            self::COUNTRY_SELECT => CountrySelect::createDisplayComponent($field, $label, $answer),
        };
    }

    /**
     * Update field options based on selection
     * 
     * @param array $options Current options array
     * @param mixed $selectedValue Value to be selected
     * @return array Updated options with selected state
     */
    public function updateOptions(array $options, $selectedValue): array
    {
        return match ($this) {
            self::SELECT => Select::updateOptions($options, $selectedValue),
            self::RADIO => Radio::updateOptions($options, $selectedValue),
            self::SELECT_PRICED => SelectPriced::updateOptions($options, $selectedValue),
            self::RADIO_PRICED => RadioPriced::updateOptions($options, $selectedValue),
            self::PLAN_TIER => PlanTier::updatePlans($options, $selectedValue),
            self::ECOMMERCE => Ecommerce::updateProducts($options, $selectedValue),
            self::ECOMMERCE_2 => Ecommerce2::updateProducts($options, $selectedValue),
            self::COUNTRY_SELECT => CountrySelect::updateOptions($options, $selectedValue),
            default => $options
        };
    }
}
