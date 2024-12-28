<?php

namespace App\Observers;

use App\Enums\LogEvent;
use App\Enums\LogName;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class ArticleObserver
{
    public function created(Article $article)
    {
        $properties = [
            'fr' => [
                'title' => $article->getTranslation('title', 'fr'),
                'description' => $article->getTranslation('description', 'fr'),
                'content' => $article->getTranslation('content', 'fr'),
            ],
            'en' => [
                'title' => $article->getTranslation('title', 'en'),
                'description' => $article->getTranslation('description', 'en'),
                'content' => $article->getTranslation('content', 'en'),
            ],
            'ar' => [
                'title' => $article->getTranslation('title', 'ar'),
                'description' => $article->getTranslation('description', 'ar'),
                'content' => $article->getTranslation('content', 'ar'),
            ],
        ];

        activity()
            ->useLog(LogName::Articles->value)
            ->event(LogEvent::Creation->value)
            ->performedOn($article)
            ->withProperties($properties)
            ->causedBy(Auth::user())
            ->log("Création d'un nouvel article");
    }

    public function updated(Article $article)
    {
        $translatableFields = ['title', 'description', 'content'];
        $changes = [];

        foreach ($translatableFields as $field) {
            if ($article->isDirty($field)) {
                foreach (['fr', 'en', 'ar'] as $locale) {
                    if ($article->getTranslation($field, $locale) !== $article->getOriginal($field)[$locale]) {
                        $changes[$field][$locale] = [
                            'ancien' => $article->getOriginal($field)[$locale],
                            'nouveau' => $article->getTranslation($field, $locale)
                        ];
                    }
                }
            }
        }

        if (!empty($changes)) {
            activity()
                ->useLog(LogName::Articles->value)
                ->event(LogEvent::Modification->value)
                ->performedOn($article)
                ->withProperties($changes)
                ->causedBy(Auth::user())
                ->log("Modification de l'article");
        }
    }

    public function deleted(Article $article)
    {
        $properties = [
            'fr' => [
                'title' => $article->getTranslation('title', 'fr'),
                'description' => $article->getTranslation('description', 'fr'),
            ],
            'en' => [
                'title' => $article->getTranslation('title', 'en'),
                'description' => $article->getTranslation('description', 'en'),
            ],
            'ar' => [
                'title' => $article->getTranslation('title', 'ar'),
                'description' => $article->getTranslation('description', 'ar'),
            ],
        ];

        activity()
            ->useLog(LogName::Articles->value)
            ->event(LogEvent::Deletion->value)
            ->performedOn($article)
            ->causedBy(Auth::user())
            ->withProperties($properties)
            ->log("Suppression de l'article");
    }
}
