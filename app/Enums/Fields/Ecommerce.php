<?php

namespace App\Enums\Fields;

use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Facades\App;

class Ecommerce
{
    public static function initializeField(array $field): array
    {
        $fieldData = [
            'type' => $field['type'],
            'data' => [
                'label' => $field['data']['label'],
                'description' => $field['data']['description'] ?? null,
                'quantity_label' => $field['data']['quantity_label'] ?? [
                    'en' => 'Quantity',
                    'fr' => 'Quantité',
                    'ar' => 'الكمية'
                ],
            ],
            'answer' => self::getDefaultAnswer($field)
        ];

        if (isset($field['data']['type'])) {
            $fieldData['data']['type'] = $field['data']['type'];
        }
        if (isset($field['data']['required'])) {
            $fieldData['data']['required'] = $field['data']['required'];
        }
        if (isset($field['data']['products'])) {
            $fieldData['data']['products'] = $field['data']['products'];

            // Enhance products with data from the Product model
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

        return $fieldData;
    }

    public static function getDefaultAnswer(array $field = []): array
    {
        return [
            'products' => []
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

        return array_merge($rules, ['array']);
    }

    public static function processFieldAnswer($answer, array $fieldData = [])
    {
        if ($answer === null || (is_array($answer) && empty($answer))) {
            return $answer;
        }

        // Simplified structure - just return the products array directly
        if (empty($answer['products'])) {
            return ['products' => []];
        }

        // Return the products array as is
        return $answer;
    }

    public static function calculateFieldPrice($answer, array $fieldData, string $preferredCurrency): float
    {
        $price = 0;

        if (empty($answer) || empty($answer['products'])) {
            return $price;
        }

        foreach ($answer['products'] as $product) {
            if (!empty($product['selected']) && $product['selected'] === true) {
                $quantity = max(1, intval($product['quantity'] ?? 1));
                $productPrice = floatval($product['price'][$preferredCurrency] ?? 0);
                $price += $productPrice * $quantity;
            }
        }

        return $price;
    }
    public static function getInvoiceDetails(array $field, string $currency = 'DZD'): array
    {
        if (empty($field['answer']['products'])) {
            return [];
        }

        $details = [];
        foreach ($field['answer']['products'] as $product) {
            if (!empty($product['selected']) && $product['selected'] === true) {
                $details[] = [
                    'title' => $product['name'] ?? '',
                    'quantity' => $product['quantity'] ?? 1,
                    'price' => $product['price'][$currency] ?? 0,

                ];
            }
        }
        return $details;
    }
    public static function isPriced(): bool
    {
        return true;
    }

    public static function needsQuantity(): bool
    {
        return true;
    }

    /**
     * Update product selection and quantity
     * 
     * @param array $products Current products array
     * @param mixed $productData Can be a product ID to toggle selection, or an array with [id, quantity]
     * @return array Updated products with selected state and quantity
     */
    public static function updateProducts(array $products, $productData): array
    {
        if (empty($products)) {
            return [];
        }

        // Handle the case where $productData is just an ID (toggle selection)
        if (!is_array($productData)) {
            $productId = $productData;

            foreach ($products as $index => $product) {
                if ($product['product_id'] == $productId) {
                    // Toggle selection status
                    $products[$index]['selected'] = !($product['selected'] ?? false);

                    // If newly selected, ensure quantity is at least 1
                    if ($products[$index]['selected'] && (!isset($products[$index]['quantity']) || $products[$index]['quantity'] < 1)) {
                        $products[$index]['quantity'] = 1;
                    }
                }
            }

            return $products;
        }

        // Handle case where $productData is [id, quantity]
        if (is_array($productData) && count($productData) >= 2) {
            $productId = $productData[0];
            $quantity = intval($productData[1]);

            foreach ($products as $index => $product) {
                if ($product['product_id'] == $productId) {
                    if ($quantity <= 0) {
                        // If quantity is 0 or negative, unselect the product
                        $products[$index]['selected'] = false;
                    } else {
                        // Update quantity and ensure product is selected
                        $products[$index]['selected'] = true;
                        $products[$index]['quantity'] = $quantity;
                    }
                }
            }
        }

        return $products;
    }

    /**
     * Create a display component for an ecommerce field
     *
     * @param array $field The field definition with type, data and answer
     * @param string $label The field label
     * @param mixed $answer The field answer value
     * @return \Filament\Infolists\Components\Component
     */
    public static function createDisplayComponent(array $field, string $label, $answer)
    {
        $locale = App::getLocale();

        // Create a Group with title-description entry and ecommerce component
        return \Filament\Infolists\Components\Group::make()
            ->schema([
                \App\Infolists\Components\TitleDescriptionEntry::make('heading')
                    ->state([
                        'title' => $label,
                        'description' => $field['data']['description'][$locale] ?? ($field['data']['description']['en'] ?? null),
                    ]),

                \App\Infolists\Components\EcommerceProductsEntry::make('ecommerce')
                    ->label('')
                    ->state($answer)
            ]);
    }
}
