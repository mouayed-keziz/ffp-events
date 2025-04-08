<?php

namespace App\Observers;

use App\Activity\EventAnnouncementActivity;
use App\Enums\LogEvent;
use App\Models\EventAnnouncement;

class EventAnnouncementObserver
{
    public function created(EventAnnouncement $eventAnnouncement)
    {
        // Create visitor form with empty fields
        $eventAnnouncement->visitorForm()->create([
            'sections' => []
        ]);

        // Log the creation using the activity class
        EventAnnouncementActivity::logCreation($eventAnnouncement);
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
                        $frenchFieldName = match ($field) {
                            'title' => 'titre',
                            'description' => 'description',
                            'content' => 'contenu',
                            'terms' => 'conditions'
                        };
                        $changes["$frenchFieldName $locale ancien"] = $originalValue;
                        $changes["$frenchFieldName $locale nouveau"] = $eventAnnouncement->getTranslation($field, $locale);
                    }
                }
            }
        }        // Handle date fields
        $dateFields = [
            'start_date' => 'date de début',
            'end_date' => 'date de fin',
            'visitor_registration_start_date' => 'date de début d\'inscription des visiteurs',
            'visitor_registration_end_date' => 'date de fin d\'inscription des visiteurs',
            'exhibitor_registration_start_date' => 'date de début d\'inscription des exposants',
            'exhibitor_registration_end_date' => 'date de fin d\'inscription des exposants',
        ];

        foreach ($dateFields as $field => $frenchName) {
            if ($eventAnnouncement->isDirty($field)) {
                $originalValue = $eventAnnouncement->getOriginal($field);
                $newValue = $eventAnnouncement->$field;

                // Format date to dd/mm/yyyy hh:mm if not null
                $formattedOriginalValue = $originalValue ? \Carbon\Carbon::parse($originalValue)->format('d/m/Y H:i') : null;
                $formattedNewValue = $newValue ? \Carbon\Carbon::parse($newValue)->format('d/m/Y H:i') : null;

                $changes["$frenchName ancien"] = $formattedOriginalValue;
                $changes["$frenchName nouveau"] = $formattedNewValue;
            }
        }

        // Handle the contact JSON field
        if ($eventAnnouncement->isDirty('contact')) {
            $originalContact = $eventAnnouncement->getOriginal('contact');
            $newContact = $eventAnnouncement->contact;

            // Check each property of the contact object
            foreach (['name', 'email', 'phone_number'] as $property) {
                if (
                    isset($originalContact[$property]) && isset($newContact[$property]) &&
                    $originalContact[$property] !== $newContact[$property]
                ) {
                    $frenchPropertyName = match ($property) {
                        'name' => 'nom',
                        'email' => 'email',
                        'phone_number' => 'téléphone'
                    };
                    $changes["contact $frenchPropertyName ancien"] = $originalContact[$property];
                    $changes["contact $frenchPropertyName nouveau"] = $newContact[$property];
                }
            }
        }

        EventAnnouncementActivity::logUpdate($eventAnnouncement, $changes);
    }

    public function deleted(EventAnnouncement $eventAnnouncement)
    {
        if (!$eventAnnouncement->isForceDeleting()) {
            EventAnnouncementActivity::logEventAction($eventAnnouncement, LogEvent::Deletion, "Suppression de l'événement");
        }
    }

    public function forceDeleted(EventAnnouncement $eventAnnouncement)
    {
        EventAnnouncementActivity::logEventAction($eventAnnouncement, LogEvent::ForceDeletion, "Suppression définitive de l'événement");
    }

    public function restored(EventAnnouncement $eventAnnouncement)
    {
        EventAnnouncementActivity::logEventAction($eventAnnouncement, LogEvent::Restoration, "Restauration de l'événement");
    }
}
