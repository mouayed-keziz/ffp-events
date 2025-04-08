<?php

namespace App\Activity;

use App\Enums\LogEvent;
use App\Enums\LogName;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProductActivity
{
    /**
     * Log a product creation
     *
     * @param Product $product
     * @return void
     */
    public static function logCreation(Product $product): void
    {
        $properties = self::buildTranslationProperties($product);

        activity()
            ->useLog(LogName::Products->value)
            ->event(LogEvent::Creation->value)
            ->performedOn($product)
            ->withProperties($properties)
            ->causedBy(self::getCurrentUser())
            ->log("Création d'un nouveau produit");
    }

    /**
     * Log a product update
     *
     * @param Product $product
     * @param array $changes
     * @return void
     */
    public static function logUpdate(Product $product, array $changes): void
    {
        if (!empty($changes)) {
            activity()
                ->useLog(LogName::Products->value)
                ->event(LogEvent::Modification->value)
                ->performedOn($product)
                ->withProperties($changes)
                ->causedBy(self::getCurrentUser())
                ->log("Modification du produit");
        }
    }

    /**
     * Log a product action (delete, force delete, restore)
     *
     * @param Product $product
     * @param LogEvent $event
     * @param string $description
     * @return void
     */
    public static function logProductAction(Product $product, LogEvent $event, string $description): void
    {
        $properties = self::buildSimpleProperties($product);

        activity()
            ->useLog(LogName::Products->value)
            ->event($event->value)
            ->performedOn($product)
            ->causedBy(self::getCurrentUser())
            ->withProperties($properties)
            ->log($description);
    }

    /**
     * Build complete properties array for a product
     *
     * @param Product $product
     * @return array
     */
    private static function buildTranslationProperties(Product $product): array
    {
        $properties = [];

        // Add translatable fields
        foreach (['fr', 'en', 'ar'] as $locale) {
            $properties["nom $locale"] = $product->getTranslation('name', $locale);
        }

        // Add non-translatable fields
        $properties["code"] = $product->code;

        return $properties;
    }

    /**
     * Build simplified properties array for deletion, restoration, etc.
     *
     * @param Product $product
     * @return array
     */
    private static function buildSimpleProperties(Product $product): array
    {
        $properties = [];

        foreach (['fr', 'en', 'ar'] as $locale) {
            $properties["nom $locale"] = $product->getTranslation('name', $locale);
        }

        $properties["code"] = $product->code;

        return $properties;
    }

    /**
     * Get logged in user from any guard (web, visitor, exhibitor)
     *
     * @return Model|null
     */
    private static function getCurrentUser(): ?Model
    {
        // Check all three guards in priority order
        foreach (['web', 'visitor', 'exhibitor'] as $guard) {
            if (Auth::guard($guard)->check()) {
                return Auth::guard($guard)->user();
            }
        }

        return null;
    }
}
