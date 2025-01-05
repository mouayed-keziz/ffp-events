<?php

namespace App\Observers;

use App\Enums\LogEvent;
use App\Enums\LogName;
use App\Models\EventAnnouncement;
use Illuminate\Support\Facades\Auth;

class EventAnnouncementObserver
{
    public function created(EventAnnouncement $eventAnnouncement)
    {
        // Create visitor form with empty fields
        $eventAnnouncement->visitorForm()->create([
            'fields' => []
        ]);

        $properties = [
            'fr' => [
                'title' => $eventAnnouncement->getTranslation('title', 'fr'),
                'description' => $eventAnnouncement->getTranslation('description', 'fr'),
                'content' => $eventAnnouncement->getTranslation('content', 'fr'),
                'terms' => $eventAnnouncement->getTranslation('terms', 'fr'),
            ],
            'en' => [
                'title' => $eventAnnouncement->getTranslation('title', 'en'),
                'description' => $eventAnnouncement->getTranslation('description', 'en'),
                'content' => $eventAnnouncement->getTranslation('content', 'en'),
                'terms' => $eventAnnouncement->getTranslation('terms', 'en'),
            ],
            'ar' => [
                'title' => $eventAnnouncement->getTranslation('title', 'ar'),
                'description' => $eventAnnouncement->getTranslation('description', 'ar'),
                'content' => $eventAnnouncement->getTranslation('content', 'ar'),
                'terms' => $eventAnnouncement->getTranslation('terms', 'ar'),
            ],
        ];

        activity()
            ->useLog(LogName::EventAnnouncements->value)
            ->event(LogEvent::Creation->value)
            ->performedOn($eventAnnouncement)
            ->withProperties($properties)
            ->causedBy(Auth::user())
            ->log("Création d'un nouvel événement");
    }

    public function updated(EventAnnouncement $eventAnnouncement)
    {
        $translatableFields = ['title', 'description', 'content', 'terms'];
        $changes = [];

        foreach ($translatableFields as $field) {
            if ($eventAnnouncement->isDirty($field)) {
                foreach (['fr', 'en', 'ar'] as $locale) {
                    $originalValue = $eventAnnouncement->getOriginal($field) ? $eventAnnouncement->getOriginal($field)[$locale] : null;
                    if ($eventAnnouncement->getTranslation($field, $locale) !== $originalValue) {
                        $changes[$field][$locale] = [
                            'ancien' => $eventAnnouncement->getOriginal($field) ? $eventAnnouncement->getOriginal($field)[$locale] : null,
                            'nouveau' => $eventAnnouncement->getTranslation($field, $locale)
                        ];
                    }
                }
            }
        }

        if (!empty($changes)) {
            activity()
                ->useLog(LogName::EventAnnouncements->value)
                ->event(LogEvent::Modification->value)
                ->performedOn($eventAnnouncement)
                ->withProperties($changes)
                ->causedBy(Auth::user())
                ->log("Modification de l'événement");
        }
    }

    public function deleted(EventAnnouncement $eventAnnouncement)
    {
        if (!$eventAnnouncement->isForceDeleting()) {
            $this->logEventAction($eventAnnouncement, LogEvent::Deletion, "Suppression de l'événement");
        }
    }

    public function forceDeleted(EventAnnouncement $eventAnnouncement)
    {
        $this->logEventAction($eventAnnouncement, LogEvent::ForceDeletion, "Suppression définitive de l'événement");
    }

    public function restored(EventAnnouncement $eventAnnouncement)
    {
        $this->logEventAction($eventAnnouncement, LogEvent::Restoration, "Restauration de l'événement");
    }

    private function logEventAction(EventAnnouncement $eventAnnouncement, LogEvent $event, string $description)
    {
        $properties = [
            'fr' => [
                'title' => $eventAnnouncement->getTranslation('title', 'fr'),
                'description' => $eventAnnouncement->getTranslation('description', 'fr'),
            ],
            'en' => [
                'title' => $eventAnnouncement->getTranslation('title', 'en'),
                'description' => $eventAnnouncement->getTranslation('description', 'en'),
            ],
            'ar' => [
                'title' => $eventAnnouncement->getTranslation('title', 'ar'),
                'description' => $eventAnnouncement->getTranslation('description', 'ar'),
            ],
        ];

        activity()
            ->useLog(LogName::EventAnnouncements->value)
            ->event($event->value)
            ->performedOn($eventAnnouncement)
            ->causedBy(Auth::user())
            ->withProperties($properties)
            ->log($description);
    }
}
