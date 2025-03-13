<?php

namespace App\Enums\Fields;

class Ecommerce
{
    public static function initializeField(array $field): array
    {
        $fieldData = [
            'type' => $field['type'],
            'data' => [
                'label' => $field['data']['label'],
                'description' => $field['data']['description'] ?? null,
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

    public static function isPriced(): bool
    {
        return true;
    }

    public static function needsQuantity(): bool
    {
        return true;
    }
}
