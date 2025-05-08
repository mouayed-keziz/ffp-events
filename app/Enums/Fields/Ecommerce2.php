<?php

namespace App\Enums\Fields;

use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Facades\App;

class Ecommerce2
{
    public static function initializeField(array $field): array
    {
        $fieldData = [
            'type' => $field['type'],
            'data' => [
                'label' => $field['data']['label'] ?? [],
                'description' => $field['data']['description'] ?? [],
                'required' => $field['data']['required'] ?? false,
                'products' => $field['data']['products'] ?? [],
                'number1_label' => $field['data']['number1_label'] ?? [],
                'number2_label' => $field['data']['number2_label'] ?? [],
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

            // Initialize answer with product data
            $fieldData['answer'] = [
                'products' => collect($field['data']['products'])->map(function ($product) use ($field) {
                    $productModel = \App\Models\Product::find($product['product_id'] ?? null);
                    return [
                        'product_id' => $product['product_id'] ?? null,
                        'name' => $productModel ? $productModel->name : null,
                        'code' => $productModel ? $productModel->code : null,
                        'selected' => false,
                        'number1' => 1,
                        'number2' => 1,
                        'number1_label' => $field['data']['number1_label'] ?? null,
                        'number2_label' => $field['data']['number2_label'] ?? null,
                        'price' => $product['price'] ?? [],
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
            return null;
        }

        // Simplified structure - just return the products array directly
        if (empty($answer['products'])) {
            return null;
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
            if (!isset($product['selected']) || $product['selected'] !== true) {
                continue;
            }

            $number1 = isset($product['number1']) ? (int)$product['number1'] : 1;
            $number2 = isset($product['number2']) ? (int)$product['number2'] : 1;

            // Get price in preferred currency
            $productPrice = $product['price'][$preferredCurrency] ?? 0;

            // Calculate total price: price * number1 * number2
            $price += $productPrice * $number1 * $number2;
        }

        return $price;
    }

    public static function getInvoiceDetails(array $field, string $currency = 'DZD'): array
    {
        if (empty($field['answer']['products'])) {
            return [];
        }

        $locale = App::getLocale();
        $details = [];

        foreach ($field['answer']['products'] as $product) {
            if (!isset($product['selected']) || $product['selected'] !== true) {
                continue;
            }

            // Try to get the product from the database to get updated info
            $productModel = null;
            if (!empty($product['product_id'])) {
                $productModel = \App\Models\Product::find($product['product_id']);
            }

            // Get product name with locale preference
            $productName = $productModel
                ? ($productModel->getTranslations('name')[$locale] ?? $product['name'])
                : ($product['name'] ?? __('Unnamed Product'));

            // Get product code
            $productCode = $product['code'] ?? ($productModel ? $productModel->code : '');

            $number1 = isset($product['number1']) ? (int)$product['number1'] : 1;
            $number2 = isset($product['number2']) ? (int)$product['number2'] : 1;
            $productPrice = $product['price'][$currency] ?? 0;

            // Include product code in the title if available
            $title = $productName;
            if (!empty($productCode)) {
                $title .= ' (' . $productCode . ')';
            }

            $details[] = [
                'title' => $title,
                'quantity' => $number1 * $number2,
                'price' => $productPrice,
                'total' => $productPrice * $number1 * $number2,
                'currency' => $currency
            ];
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
     * @param mixed $productData Data with [id, number1, number2]
     * @return array Updated products with selected state and numbers
     */
    public static function updateProducts(array $products, $productData): array
    {
        if (empty($products)) {
            return [];
        }

        // Handle the case where $productData is just an ID (toggle selection)
        if (!is_array($productData)) {
            $productId = $productData;
            foreach ($products as $key => $product) {
                if ($product['product_id'] == $productId) {
                    $products[$key]['selected'] = !($product['selected'] ?? false);
                }
            }
            return $products;
        }

        // Handle case where $productData is [id, number1, number2]
        if (is_array($productData) && count($productData) >= 3) {
            $productId = $productData[0];
            $number1 = (int)$productData[1];
            $number2 = (int)$productData[2];

            foreach ($products as $key => $product) {
                if ($product['product_id'] == $productId) {
                    $products[$key]['number1'] = $number1;
                    $products[$key]['number2'] = $number2;
                }
            }
        }

        return $products;
    }

    /**
     * Create a display component for an ecommerce2 field
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
                TextEntry::make('label')
                    ->label($label)
                    ->default(function () use ($field, $locale) {
                        $description = $field['data']['description'][$locale] ?? '';
                        return $description ? "({$description})" : '';
                    }),
                \App\Infolists\Components\Ecommerce2ProductEntry::make('products')
                    ->state($answer)
            ]);
    }
}
