<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Arr;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Str;

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
    case PLAN_TIER = "plan_tier";

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
            self::PLAN_TIER->value       => 'heroicon-o-table-cells',
        ];

        return $icons[$this->value] ?? 'heroicon-o-question-mark';
    }

    /**
     * Initialize a field structure based on its type
     */
    public function initializeField(array $field): array
    {
        $fieldData = [
            'type' => $field['type'],
            'data' => [
                'label' => $field['data']['label'],
                'description' => $field['data']['description'] ?? null,
            ],
            'answer' => $this->getDefaultAnswer($field)
        ];

        // Copy any additional field-specific data
        if (isset($field['data']['type'])) {
            $fieldData['data']['type'] = $field['data']['type'];
        }
        if (isset($field['data']['required'])) {
            $fieldData['data']['required'] = $field['data']['required'];
        }
        if (isset($field['data']['options'])) {
            $fieldData['data']['options'] = $field['data']['options'];
        }
        if (isset($field['data']['file_type'])) {
            $fieldData['data']['file_type'] = $field['data']['file_type'];
        }
        if (isset($field['data']['plan_tier_id'])) {
            $fieldData['data']['plan_tier_id'] = $field['data']['plan_tier_id'];
        }
        if (isset($field['data']['price'])) {
            $fieldData['data']['price'] = $field['data']['price'];
        }
        if (isset($field['data']['products'])) {
            $fieldData['data']['products'] = $field['data']['products'];

            // Enhance products with data from the Product model
            if ($this === self::ECOMMERCE) {
                foreach ($fieldData['data']['products'] as $index => $product) {
                    if (isset($product['product_id'])) {
                        $productModel = \App\Models\Product::find($product['product_id']);
                        if ($productModel) {
                            $fieldData['data']['products'][$index]['product_details'] = [
                                'name' => $productModel->name,
                                'image' => $productModel->image,
                                'code' => $productModel->code,
                            ];
                        }
                    }
                }

                // Initialize products with empty selection and quantity
                $fieldData['answer'] = [
                    'products' => collect($field['data']['products'])->map(function ($product) {
                        $productModel = \App\Models\Product::find($product['product_id'] ?? null);
                        return [
                            'product_id' => $product['product_id'] ?? null,
                            'name' => $productModel ? $productModel->name : null,
                            'code' => $productModel ? $productModel->code : null,
                            'selected' => false,
                            'quantity' => 1,
                            'price' => $product['price'] ?? []
                        ];
                    })->toArray()
                ];
            }
        }

        // Initialize structured answers for regular select, radio, checkbox fields
        if (in_array($this, [self::SELECT, self::RADIO, self::CHECKBOX])) {
            if (isset($field['data']['options'])) {
                // Initialize the answer with all available options (none selected)
                $fieldData['answer']['options'] = collect($field['data']['options'])->map(function ($option) {
                    return [
                        'option' => $option['option'] ?? [],
                        'selected' => false,
                        'value' => $option['option'][app()->getLocale()] ?? ($option['option']['fr'] ?? '')
                    ];
                })->toArray();
            }
        }

        // Initialize structured answers for priced select, radio, checkbox fields
        if (in_array($this, [self::SELECT_PRICED, self::RADIO_PRICED, self::CHECKBOX_PRICED])) {
            if (isset($field['data']['options'])) {
                // Initialize the answer with all available options (none selected)
                $fieldData['answer']['options'] = collect($field['data']['options'])->map(function ($option) {
                    return [
                        'option' => $option['option'] ?? [],
                        'price' => $option['price'] ?? [],
                        'selected' => false,
                        'value' => $option['option'][app()->getLocale()] ?? ($option['option']['fr'] ?? '')
                    ];
                })->toArray();
            }
        }

        // For plan tier, get associated plans
        if ($this === self::PLAN_TIER && isset($field['data']['plan_tier_id'])) {
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

    /**
     * Get default answer for this field type
     */
    public function getDefaultAnswer(array $field = [])
    {
        return match ($this) {
            self::CHECKBOX => [
                'options' => [] // New structured format for checkbox
            ],
            self::CHECKBOX_PRICED => [
                'options' => [] // Structured format for checkbox priced
            ],
            self::UPLOAD => null,
            self::INPUT => $this->getInputDefaultAnswer($field),
            self::ECOMMERCE => [
                'products' => [] // Structure with products array
            ],
            self::SELECT => [
                'options' => [] // New structured format for select
            ],
            self::RADIO => [
                'options' => [] // New structured format for radio
            ],
            self::SELECT_PRICED => [
                'options' => [] // Structured format for select priced
            ],
            self::RADIO_PRICED => [
                'options' => [] // Structured format for radio priced
            ],
            self::PLAN_TIER => [
                'plans' => [] // Structure with plans array
            ],
            default => '',
        };
    }

    /**
     * Get input-specific default answer
     */
    private function getInputDefaultAnswer(array $field): string
    {
        if (isset($field['data']['type'])) {
            $inputType = FormInputType::tryFrom($field['data']['type']);
            if ($inputType) {
                return $inputType->getDefaultAnswer();
            }
        }
        return '';
    }

    /**
     * Get validation rules for this field type
     */
    public function getValidationRules(array $field): array
    {
        $rules = [];

        // Check if field is required
        if (Arr::get($field, 'data.required', false)) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        // Add field-specific rules
        $additionalRules = match ($this) {
            self::INPUT => $this->getInputValidationRules($field),
            self::UPLOAD => $this->getFileUploadValidationRules($field),
            self::CHECKBOX, self::CHECKBOX_PRICED => ['array'],
            self::ECOMMERCE => ['array'],
            default => []
        };

        return array_merge($rules, $additionalRules);
    }

    /**
     * Get validation rules for input fields
     */
    private function getInputValidationRules(array $field): array
    {
        if (isset($field['data']['type'])) {
            $inputType = FormInputType::tryFrom($field['data']['type']);
            if ($inputType) {
                return $inputType->getValidationRules();
            }
        }
        return ['string'];
    }

    /**
     * Get validation rules for file upload fields
     */
    private function getFileUploadValidationRules(array $field): array
    {
        $fileTypeValue = $field['data']['file_type'] ?? FileUploadType::ANY->value;
        $fileType = FileUploadType::tryFrom($fileTypeValue) ?? FileUploadType::ANY;

        return $fileType->getValidationRules();
    }

    /**
     * Process field answer for submission
     */
    public function processFieldAnswer($answer, array $fieldData = [])
    {
        if ($answer === null || (is_array($answer) && empty($answer))) {
            return $answer;
        }

        $currentLocale = app()->getLocale();

        // Process different field types
        switch ($this) {
            case self::SELECT:
            case self::RADIO:
                // Use the structured answer with options array
                if (empty($answer['options'])) {
                    return ['options' => []];
                }

                // Find the selected option and prepare for submission
                $selectedOptions = [];
                foreach ($answer['options'] as $option) {
                    if (!empty($option['selected']) && $option['selected'] === true) {
                        $selectedOptions[] = [
                            'option' => $option['option'],
                            'selected' => true,
                            'value' => $option['value']
                        ];
                    }
                }

                // For radio/select, we only expect one selected option
                return [
                    'options' => $answer['options'],
                    'selected_option' => $selectedOptions[0] ?? null
                ];

            case self::SELECT_PRICED:
            case self::RADIO_PRICED:
                // Just return the structured answer with options array
                if (empty($answer['options'])) {
                    return ['options' => []];
                }

                // Return the options array with selected info
                return $answer;

            case self::CHECKBOX:
                // Use the structured answer with options array for checkboxes
                if (empty($answer['options'])) {
                    return ['options' => []];
                }

                // Find all selected options and prepare for submission
                $selectedOptions = [];
                foreach ($answer['options'] as $option) {
                    if (!empty($option['selected']) && $option['selected'] === true) {
                        $selectedOptions[] = [
                            'option' => $option['option'],
                            'selected' => true,
                            'value' => $option['value']
                        ];
                    }
                }

                return [
                    'options' => $answer['options'],
                    'selected_options' => $selectedOptions
                ];

            case self::CHECKBOX_PRICED:
                // Just return the structured answer with options array
                if (empty($answer['options'])) {
                    return ['options' => []];
                }

                // Return the options array with selected info
                return $answer;

            case self::ECOMMERCE:
                // Simplified structure - just return the products array directly
                if (empty($answer['products'])) {
                    return ['products' => []];
                }

                // Return the products array as is
                return $answer;

            case self::PLAN_TIER:
                // Simplified structure - just return the plans array directly
                if (empty($answer['plans'])) {
                    return ['plans' => []];
                }

                // Return the plans array as is
                return $answer;

            default:
                // For other field types, just return the answer as is
                return $answer;
        }
    }

    /**
     * Find the option translations for a given answer value
     */
    private function findOptionTranslations(array $options, $answerValue): array
    {
        $currentLocale = app()->getLocale();

        // Find the option with matching value in current locale
        foreach ($options as $option) {
            if (isset($option['option'][$currentLocale]) && $option['option'][$currentLocale] === $answerValue) {
                return $option['option']; // Return all translations
            }
        }

        // Fallback: Return the answer value keyed by current locale
        return [$currentLocale => $answerValue];
    }

    /**
     * Find the price data for a given option
     */
    private function findOptionPrice(array $options, $answerValue): array
    {
        $currentLocale = app()->getLocale();

        // Find the option with matching value in current locale
        foreach ($options as $option) {
            if (isset($option['option'][$currentLocale]) && $option['option'][$currentLocale] === $answerValue) {
                return $option['price'] ?? []; // Return price data
            }
        }

        return [];
    }

    /**
     * Calculate price for this field based on the answers and preferred currency
     */
    public function calculateFieldPrice($answer, array $fieldData, string $preferredCurrency): float
    {
        $price = 0;

        switch ($this) {
            case self::SELECT_PRICED:
            case self::RADIO_PRICED:
                // Handle the new structure with options array
                if (empty($answer) || empty($answer['options'])) {
                    break;
                }

                // For select/radio, find and add the single selected option price
                foreach ($answer['options'] as $optionData) {
                    if (!empty($optionData['selected']) && $optionData['selected'] === true) {
                        $optionPrice = floatval($optionData['price'][$preferredCurrency] ?? 0);
                        $price = $optionPrice;
                        break;
                    }
                }
                break;

            case self::CHECKBOX_PRICED:
                // Handle the new structure with options array
                if (empty($answer) || empty($answer['options'])) {
                    break;
                }

                // For checkbox, sum up the prices of all selected options
                foreach ($answer['options'] as $optionData) {
                    if (!empty($optionData['selected']) && $optionData['selected'] === true) {
                        $optionPrice = floatval($optionData['price'][$preferredCurrency] ?? 0);
                        $price += $optionPrice;
                    }
                }
                break;

            case self::ECOMMERCE:
                // Handle the simplified structure with products array
                if (empty($answer) || empty($answer['products'])) {
                    break;
                }

                foreach ($answer['products'] as $product) {
                    if (!empty($product['selected']) && $product['selected'] === true) {
                        $quantity = max(1, intval($product['quantity'] ?? 1));
                        $productPrice = floatval($product['price'][$preferredCurrency] ?? 0);
                        $price += $productPrice * $quantity;
                    }
                }
                break;

            case self::PLAN_TIER:
                // Handle simplified structure with plans array
                if (empty($answer) || empty($answer['plans'])) {
                    break;
                }

                // Find the selected plan
                foreach ($answer['plans'] as $plan) {
                    if (!empty($plan['selected']) && $plan['selected'] === true) {
                        // Get price from plan details
                        $price = floatval($plan['price'][$preferredCurrency] ?? 0);
                        break;
                    }
                }
                break;
        }

        return $price;
    }

    /**
     * Check if this field type is priced (has monetary value)
     */
    public function isPriced(): bool
    {
        return in_array($this, [
            self::SELECT_PRICED,
            self::RADIO_PRICED,
            self::CHECKBOX_PRICED,
            self::ECOMMERCE,
            self::PLAN_TIER
        ]);
    }

    /**
     * Check if this field type needs quantity field
     */
    public function needsQuantity(): bool
    {
        return $this === self::ECOMMERCE;
    }
}
