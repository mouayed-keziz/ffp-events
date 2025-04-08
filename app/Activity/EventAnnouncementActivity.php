<?php

namespace App\Activity;

use App\Enums\LogEvent;
use App\Enums\LogName;
use App\Models\EventAnnouncement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EventAnnouncementActivity
{
    /**
     * Log an event announcement creation
     *
     * @param EventAnnouncement $eventAnnouncement
     * @return void
     */
    public static function logCreation(EventAnnouncement $eventAnnouncement): void
    {
        $properties = self::buildTranslationProperties($eventAnnouncement);

        activity()
            ->useLog(LogName::EventAnnouncements->value)
            ->event(LogEvent::Creation->value)
            ->performedOn($eventAnnouncement)
            ->withProperties($properties)
            ->causedBy(self::getCurrentUser())
            ->log("Création d'un nouvel événement");
    }

    /**
     * Log an event announcement update
     *
     * @param EventAnnouncement $eventAnnouncement
     * @param array $changes
     * @return void
     */
    public static function logUpdate(EventAnnouncement $eventAnnouncement, array $changes): void
    {
        if (!empty($changes)) {
            activity()
                ->useLog(LogName::EventAnnouncements->value)
                ->event(LogEvent::Modification->value)
                ->performedOn($eventAnnouncement)
                ->withProperties($changes)
                ->causedBy(self::getCurrentUser())
                ->log("Modification de l'événement");
        }
    }

    /**
     * Log an event announcement action (delete, force delete, restore)
     *
     * @param EventAnnouncement $eventAnnouncement
     * @param LogEvent $event
     * @param string $description
     * @return void
     */
    public static function logEventAction(EventAnnouncement $eventAnnouncement, LogEvent $event, string $description): void
    {
        $properties = self::buildSimpleProperties($eventAnnouncement);

        activity()
            ->useLog(LogName::EventAnnouncements->value)
            ->event($event->value)
            ->performedOn($eventAnnouncement)
            ->causedBy(self::getCurrentUser())
            ->withProperties($properties)
            ->log($description);
    }

    /**
     * Build complete properties array for an event announcement
     *
     * @param EventAnnouncement $eventAnnouncement
     * @return array
     */
    private static function buildTranslationProperties(EventAnnouncement $eventAnnouncement): array
    {
        $properties = [];

        // Add translatable fields
        foreach (['fr', 'en', 'ar'] as $locale) {
            $properties["titre $locale"] = $eventAnnouncement->getTranslation('title', $locale);
            $properties["description $locale"] = $eventAnnouncement->getTranslation('description', $locale);
            $properties["contenu $locale"] = $eventAnnouncement->getTranslation('content', $locale);
            $properties["conditions $locale"] = $eventAnnouncement->getTranslation('terms', $locale);
        }

        // Add non-translatable fields
        $properties["lieu"] = $eventAnnouncement->location;
        $properties["date debut"] = $eventAnnouncement->start_date?->format('Y-m-d H:i:s');
        $properties["date fin"] = $eventAnnouncement->end_date?->format('Y-m-d H:i:s');
        $properties["date debut inscription visiteur"] = $eventAnnouncement->visitor_registration_start_date?->format('Y-m-d H:i:s');
        $properties["date fin inscription visiteur"] = $eventAnnouncement->visitor_registration_end_date?->format('Y-m-d H:i:s');
        $properties["date debut inscription exposant"] = $eventAnnouncement->exhibitor_registration_start_date?->format('Y-m-d H:i:s');
        $properties["date fin inscription exposant"] = $eventAnnouncement->exhibitor_registration_end_date?->format('Y-m-d H:i:s');
        $properties["site web"] = $eventAnnouncement->website_url;

        // Handle contact array with > notation
        if (is_array($eventAnnouncement->contact)) {
            foreach ($eventAnnouncement->contact as $key => $value) {
                $properties["contact > $key"] = $value;
            }
        }

        // Handle currencies array with > notation
        if (is_array($eventAnnouncement->currencies)) {
            foreach ($eventAnnouncement->currencies as $key => $value) {
                $properties["monnaies > $key"] = $value;
            }
        }

        return $properties;
    }

    /**
     * Build simplified properties array for deletion, restoration, etc.
     *
     * @param EventAnnouncement $eventAnnouncement
     * @return array
     */
    private static function buildSimpleProperties(EventAnnouncement $eventAnnouncement): array
    {
        $properties = [];

        foreach (['fr', 'en', 'ar'] as $locale) {
            $properties["titre $locale"] = $eventAnnouncement->getTranslation('title', $locale);
            $properties["description $locale"] = $eventAnnouncement->getTranslation('description', $locale);
        }

        $properties["date debut"] = $eventAnnouncement->start_date?->format('Y-m-d H:i:s');
        $properties["date fin"] = $eventAnnouncement->end_date?->format('Y-m-d H:i:s');

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
