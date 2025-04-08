<?php

namespace App\Observers;

use App\Activity\ArticleActivity;
use App\Enums\LogEvent;
use App\Models\Article;

class ArticleObserver
{
    public function created(Article $article)
    {
        // Log the creation using the activity class
        ArticleActivity::logCreation($article);
    }

    public function updated(Article $article)
    {
        $translatableFields = ['title', 'description', 'content'];
        $changes = [];

        foreach ($translatableFields as $field) {
            if ($article->isDirty($field)) {
                foreach (['fr', 'en', 'ar'] as $locale) {
                    $originalValue = $article->getOriginal($field) ? $article->getOriginal($field)[$locale] : null;
                    if ($article->getTranslation($field, $locale) !== $originalValue) {
                        $frenchFieldName = match ($field) {
                            'title' => 'titre',
                            'description' => 'description',
                            'content' => 'contenu'
                        };
                        $changes["$frenchFieldName $locale ancien"] = $originalValue;
                        $changes["$frenchFieldName $locale nouveau"] = $article->getTranslation($field, $locale);
                    }
                }
            }
        }

        ArticleActivity::logUpdate($article, $changes);
    }

    public function deleted(Article $article)
    {
        if (!$article->isForceDeleting()) {
            ArticleActivity::logArticleAction($article, LogEvent::Deletion, "Suppression de l'article");
        }
    }

    public function forceDeleted(Article $article)
    {
        ArticleActivity::logArticleAction($article, LogEvent::ForceDeletion, "Suppression définitive de l'article");
    }

    public function restored(Article $article)
    {
        ArticleActivity::logArticleAction($article, LogEvent::Restoration, "Restauration de l'article");
    }
}
