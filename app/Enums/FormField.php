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
                $fieldData['answer'] = [];
                foreach ($field['data']['products'] as $product) {
                    $fieldData['answer'][$product['product_id']] = [
                        'selected' => false,
                        'quantity' => 1
                    ];
                }
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
            self::CHECKBOX, self::CHECKBOX_PRICED => [],
            self::UPLOAD => null,
            self::INPUT => $this->getInputDefaultAnswer($field),
            self::ECOMMERCE => [], // Empty array for ecommerce products
            self::SELECT_PRICED, self::RADIO_PRICED, self::PLAN_TIER => '',
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
     * 
     * This enhanced version includes all translations and price information
     * to ensure the submitted data has complete information
     */
    public function processFieldAnswer($answer, array $fieldData = [])
    {
        if ($answer === null || (is_array($answer) && empty($answer))) {
            return $answer;
        }

        $currentLocale = app()->getLocale();

        // Special case for file uploads
        if ($this === self::UPLOAD) {
            if ($answer instanceof TemporaryUploadedFile) {
                // File uploads are handled separately with a UUID
                return (string) Str::uuid();
            }
            return $answer; // Return the fileId if it's already processed
        }

        // Process different field types
        switch ($this) {
            case self::SELECT:
            case self::RADIO:
                // Single option selection - return with all translations
                return $this->findOptionTranslations($fieldData['options'] ?? [], $answer);

            case self::SELECT_PRICED:
            case self::RADIO_PRICED:
                // Priced single option - return both option and price data
                $option = $this->findOptionTranslations($fieldData['options'] ?? [], $answer);
                $price = $this->findOptionPrice($fieldData['options'] ?? [], $answer);

                return [
                    'option' => $option,
                    'price' => $price
                ];

            case self::CHECKBOX:
                // Multiple options - return array of translated options
                if (is_array($answer)) {
                    $translatedAnswers = [];
                    foreach ($answer as $selectedValue) {
                        $translatedAnswers[] = $this->findOptionTranslations($fieldData['options'] ?? [], $selectedValue);
                    }
                    return $translatedAnswers;
                }
                return $answer;

            case self::CHECKBOX_PRICED:
                // Multiple priced options
                if (is_array($answer)) {
                    $translatedAnswers = [];
                    foreach ($answer as $selectedValue) {
                        $option = $this->findOptionTranslations($fieldData['options'] ?? [], $selectedValue);
                        $price = $this->findOptionPrice($fieldData['options'] ?? [], $selectedValue);

                        $translatedAnswers[] = [
                            'option' => $option,
                            'price' => $price
                        ];
                    }
                    return $translatedAnswers;
                }
                return $answer;

            case self::ECOMMERCE:
                // E-commerce products with quantities
                if (is_array($answer)) {
                    $processedAnswer = [];
                    foreach ($answer as $productId => $productData) {
                        if (!empty($productData['selected']) && $productData['selected'] === true) {
                            $product = \App\Models\Product::find($productId);

                            // Find product price from field data
                            $price = [];
                            foreach ($fieldData['products'] ?? [] as $productItem) {
                                if ($productItem['product_id'] == $productId) {
                                    $price = $productItem['price'] ?? [];
                                    break;
                                }
                            }

                            $processedAnswer[$productId] = [
                                'product_id' => $productId,
                                'name' => $product ? $product->name : null,
                                'code' => $product ? $product->code : null,
                                'quantity' => $productData['quantity'] ?? 1,
                                'selected' => true,
                                'price' => $price
                            ];
                        }
                    }
                    return $processedAnswer;
                }
                return $answer;

            case self::PLAN_TIER:
                // Plan tier with pricing
                $planTier = \App\Models\PlanTier::find($fieldData['plan_tier_id'] ?? null);
                $planId = $answer;

                // Find plan price
                $price = [];
                if (isset($fieldData['plan_tier_details'])) {
                    foreach ($fieldData['plan_tier_details']['plans'] ?? [] as $plan) {
                        if ($plan['id'] == $planId) {
                            $price = $plan['price'] ?? [];
                            break;
                        }
                    }
                }

                return [
                    'plan_tier_id' => $fieldData['plan_tier_id'] ?? null,
                    'plan_id' => $planId,
                    'title' => $planTier ? $planTier->title : null,
                    'price' => $price
                ];

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
                if (!empty($answer)) {
                    $optionPrice = 0;
                    $currentLocale = app()->getLocale();

                    // Handle different answer formats
                    if (is_array($answer) && isset($answer['option'])) {
                        // Handle processed answers
                        foreach ($fieldData['options'] ?? [] as $option) {
                            if ($option['option'][$currentLocale] === $answer['option'][$currentLocale]) {
                                $optionPrice = floatval($option['price'][$preferredCurrency] ?? 0);
                                break;
                            }
                        }
                    } else {
                        // Handle raw answers (string)
                        foreach ($fieldData['options'] ?? [] as $option) {
                            if ($option['option'][$currentLocale] === $answer) {
                                $optionPrice = floatval($option['price'][$preferredCurrency] ?? 0);
                                break;
                            }
                        }
                    }
                    $price = $optionPrice;
                }
                break;

            case self::CHECKBOX_PRICED:
                if (is_array($answer)) {
                    foreach ($answer as $selectedOption) {
                        $optionPrice = 0;
                        $currentLocale = app()->getLocale();

                        // Handle different answer formats
                        if (is_array($selectedOption) && isset($selectedOption['option'])) {
                            // Already processed answers
                            foreach ($fieldData['options'] ?? [] as $option) {
                                if ($option['option'][$currentLocale] === $selectedOption['option'][$currentLocale]) {
                                    $optionPrice = floatval($option['price'][$preferredCurrency] ?? 0);
                                    break;
                                }
                            }
                        } else {
                            // Raw answers (string)
                            foreach ($fieldData['options'] ?? [] as $option) {
                                if ($option['option'][$currentLocale] === $selectedOption) {
                                    $optionPrice = floatval($option['price'][$preferredCurrency] ?? 0);
                                    break;
                                }
                            }
                        }

                        $price += $optionPrice;
                    }
                }
                break;

            case self::ECOMMERCE:
                if (is_array($answer)) {
                    foreach ($answer as $productId => $productData) {
                        if (!isset($productData['selected']) || $productData['selected'] !== true) {
                            continue;
                        }

                        // Find the product price
                        foreach ($fieldData['products'] ?? [] as $product) {
                            if ($product['product_id'] == $productId) {
                                $productPrice = floatval($product['price'][$preferredCurrency] ?? 0);
                                $quantity = intval($productData['quantity'] ?? 1);
                                $price += $productPrice * $quantity;
                                break;
                            }
                        }
                    }
                }
                break;

            case self::PLAN_TIER:
                if (empty($answer)) {
                    break;
                }

                // Handle different answer formats
                $planId = is_array($answer) && isset($answer['plan_id']) ? $answer['plan_id'] : $answer;

                // Get price from plan tier details
                if (isset($fieldData['plan_tier_details'])) {
                    foreach ($fieldData['plan_tier_details']['plans'] ?? [] as $plan) {
                        if ($plan['id'] == $planId) {
                            $planPrice = floatval($plan['price'][$preferredCurrency] ?? 0);
                            $price = $planPrice;
                            break;
                        }
                    }
                }
                // Fallback to direct price
                else if (isset($fieldData['price']) && isset($fieldData['price'][$preferredCurrency])) {
                    $price = floatval($fieldData['price'][$preferredCurrency]);
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
