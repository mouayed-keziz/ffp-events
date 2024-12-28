<?php

namespace App\Observers;

use App\Enums\LogEvent;
use App\Enums\LogName;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryObserver
{
    public function created(Category $category)
    {
        $properties = [
            'fr' => $category->getTranslation('name', 'fr'),
            'en' => $category->getTranslation('name', 'en'),
            'ar' => $category->getTranslation('name', 'ar')
        ];

        activity()
            ->useLog(LogName::Categories->value)
            ->event(LogEvent::Creation->value)
            ->performedOn($category)
            ->withProperties($properties)
            ->causedBy(Auth::user())
            ->log("Création d'une nouvelle catégorie");
    }

    public function updated(Category $category)
    {
        if ($category->isDirty('name')) {
            $changes = [];
            foreach (['fr', 'en', 'ar'] as $locale) {
                if ($category->getTranslation('name', $locale) !== $category->getOriginal('name')[$locale]) {
                    $changes[$locale] = [
                        'ancien' => $category->getOriginal('name')[$locale],
                        'nouveau' => $category->getTranslation('name', $locale)
                    ];
                }
            }

            if (!empty($changes)) {
                activity()
                    ->useLog(LogName::Categories->value)
                    ->event(LogEvent::Modification->value)
                    ->performedOn($category)
                    ->withProperties($changes)
                    ->causedBy(Auth::user())
                    ->log("Modification du nom de la catégorie");
            }
        }
    }

    public function deleted(Category $category)
    {
        activity()
            ->useLog(LogName::Categories->value)
            ->event(LogEvent::Deletion->value)
            ->performedOn($category)
            ->causedBy(Auth::user())
            ->withProperties([
                'nom_fr' => $category->getTranslation('name', 'fr'),
                'nom_en' => $category->getTranslation('name', 'en'),
                'nom_ar' => $category->getTranslation('name', 'ar')
            ])
            ->log("Suppression de la catégorie");
    }
}
