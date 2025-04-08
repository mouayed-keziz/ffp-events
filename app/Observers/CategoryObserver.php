<?php

namespace App\Observers;

use App\Activity\CategoryActivity;
use App\Models\Category;

class CategoryObserver
{
    public function created(Category $category)
    {
        // Log the creation using the activity class
        CategoryActivity::logCreation($category);
    }

    public function updated(Category $category)
    {
        if ($category->isDirty('name')) {
            $changes = [];
            foreach (['fr', 'en', 'ar'] as $locale) {
                $originalValue = $category->getOriginal('name') ? $category->getOriginal('name')[$locale] : null;
                if ($category->getTranslation('name', $locale) !== $originalValue) {
                    $changes["nom $locale ancien"] = $originalValue;
                    $changes["nom $locale nouveau"] = $category->getTranslation('name', $locale);
                }
            }

            CategoryActivity::logUpdate($category, $changes);
        }
    }

    public function deleted(Category $category)
    {
        CategoryActivity::logDeletion($category);
    }
}
