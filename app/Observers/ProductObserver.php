<?php

namespace App\Observers;

use App\Activity\ProductActivity;
use App\Enums\LogEvent;
use App\Models\Product;

class ProductObserver
{
    public function created(Product $product)
    {
        // Log the creation using the activity class
        ProductActivity::logCreation($product);
    }

    public function updated(Product $product)
    {
        $translatableFields = ['name'];
        $changes = [];

        foreach ($translatableFields as $field) {
            if ($product->isDirty($field)) {
                foreach (['fr', 'en', 'ar'] as $locale) {
                    $originalValue = $product->getOriginal($field) ? $product->getOriginal($field)[$locale] : null;
                    if ($product->getTranslation($field, $locale) !== $originalValue) {
                        $frenchFieldName = match ($field) {
                            'name' => 'nom'
                        };
                        $changes["$frenchFieldName $locale ancien"] = $originalValue;
                        $changes["$frenchFieldName $locale nouveau"] = $product->getTranslation($field, $locale);
                    }
                }
            }
        }

        // Handle non-translatable fields
        $regularFields = [
            'code' => 'code'
        ];

        foreach ($regularFields as $field => $frenchName) {
            if ($product->isDirty($field)) {
                $originalValue = $product->getOriginal($field);
                $newValue = $product->$field;

                $changes["$frenchName ancien"] = $originalValue;
                $changes["$frenchName nouveau"] = $newValue;
            }
        }

        ProductActivity::logUpdate($product, $changes);
    }

    public function deleted(Product $product)
    {
        if (!$product->isForceDeleting()) {
            ProductActivity::logProductAction($product, LogEvent::Deletion, "Suppression du produit");
        }
    }

    public function forceDeleted(Product $product)
    {
        ProductActivity::logProductAction($product, LogEvent::ForceDeletion, "Suppression définitive du produit");
    }

    public function restored(Product $product)
    {
        ProductActivity::logProductAction($product, LogEvent::Restoration, "Restauration du produit");
    }
}
