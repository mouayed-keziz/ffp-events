<?php

namespace App\Activity;

use App\Enums\LogEvent;
use App\Enums\LogName;
use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ArticleActivity
{
    /**
     * Log an article creation
     *
     * @param Article $article
     * @return void
     */
    public static function logCreation(Article $article): void
    {
        $properties = self::buildTranslationProperties($article);

        activity()
            ->useLog(LogName::Articles->value)
            ->event(LogEvent::Creation->value)
            ->performedOn($article)
            ->withProperties($properties)
            ->causedBy(self::getCurrentUser())
            ->log("Création d'un nouvel article");
    }

    /**
     * Log an article update
     *
     * @param Article $article
     * @param array $changes
     * @return void
     */
    public static function logUpdate(Article $article, array $changes): void
    {
        if (!empty($changes)) {
            activity()
                ->useLog(LogName::Articles->value)
                ->event(LogEvent::Modification->value)
                ->performedOn($article)
                ->withProperties($changes)
                ->causedBy(self::getCurrentUser())
                ->log("Modification de l'article");
        }
    }

    /**
     * Log an article action (delete, force delete, restore)
     *
     * @param Article $article
     * @param LogEvent $event
     * @param string $description
     * @return void
     */
    public static function logArticleAction(Article $article, LogEvent $event, string $description): void
    {
        $properties = self::buildSimpleProperties($article);

        activity()
            ->useLog(LogName::Articles->value)
            ->event($event->value)
            ->performedOn($article)
            ->causedBy(self::getCurrentUser())
            ->withProperties($properties)
            ->log($description);
    }

    /**
     * Build complete properties array for an article
     *
     * @param Article $article
     * @return array
     */
    private static function buildTranslationProperties(Article $article): array
    {
        $properties = [];

        // Add translatable fields with flattened structure
        foreach (['fr', 'en', 'ar'] as $locale) {
            $properties["titre $locale"] = $article->getTranslation('title', $locale);
            $properties["description $locale"] = $article->getTranslation('description', $locale);
            $properties["contenu $locale"] = $article->getTranslation('content', $locale);
        }

        return $properties;
    }

    /**
     * Build simplified properties array for deletion, restoration, etc.
     *
     * @param Article $article
     * @return array
     */
    private static function buildSimpleProperties(Article $article): array
    {
        $properties = [];

        foreach (['fr', 'en', 'ar'] as $locale) {
            $properties["titre $locale"] = $article->getTranslation('title', $locale);
            $properties["description $locale"] = $article->getTranslation('description', $locale);
        }

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
