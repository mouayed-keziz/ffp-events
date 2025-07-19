<?php

return [
    'resource' => [
        'label' => 'Mon Événement',
        'plural_label' => 'Mes Événements',
    ],

    'navigation' => [
        'group' => 'Mes Événements',
    ],

    'fields' => [
        'title' => 'Titre',
        'description' => 'Description',
        'location' => 'Lieu',
        'start_date' => 'Date de Début',
        'end_date' => 'Date de Fin',
        'visitor_registration_start_date' => 'Début d\'Inscription Visiteurs',
        'visitor_registration_end_date' => 'Fin d\'Inscription Visiteurs',
        'exhibitor_registration_start_date' => 'Début d\'Inscription Exposants',
        'exhibitor_registration_end_date' => 'Fin d\'Inscription Exposants',
        'website_url' => 'URL du Site Web',
        'contact' => 'Contact',
        'currencies' => 'Devises',
        'image' => 'Image de l\'Événement',
        'terms' => 'Conditions Générales',
        'content' => 'Contenu',
    ],

    'actions' => [
        'view' => 'Voir l\'Événement',
        'edit' => 'Modifier l\'Événement',
    ],

    'infolist' => [
        'sections' => [
            'basic_info' => 'Informations de Base',
            'dates' => 'Dates de l\'Événement',
            'registration_dates' => 'Périodes d\'Inscription',
            'additional_info' => 'Informations Supplémentaires',
        ],
    ],

    'table' => [
        'columns' => [
            'title' => 'Titre',
            'location' => 'Lieu',
            'start_date' => 'Date de Début',
            'end_date' => 'Date de Fin',
            'status' => 'Statut',
        ],
        'filters' => [
            'status' => 'Statut',
            'date_range' => 'Plage de Dates',
        ],
    ],

    'relation_managers' => [
        'current_attendees' => [
            'title' => 'Participants Actuels',
            'columns' => [
                'badge_name' => 'Nom',
                'badge_email' => 'Email',
                'badge_company' => 'Entreprise',
                'badge_position' => 'Poste',
                'checked_in_at' => 'Enregistré à',
                'checked_in_by_user' => 'Enregistré par',
            ],
            'filters' => [
                'checked_in_date' => 'Date d\'Enregistrement',
                'company' => 'Entreprise',
                'position' => 'Poste',
            ],
        ],
        'badge_check_logs' => [
            'title' => 'Journaux de Vérification des Badges',
            'columns' => [
                'badge_name' => 'Nom',
                'badge_email' => 'Email',
                'badge_company' => 'Entreprise',
                'action' => 'Action',
                'action_time' => 'Heure de l\'Action',
                'checked_by_user' => 'Vérifié par',
                'note' => 'Note',
            ],
            'filters' => [
                'action' => 'Action',
                'action_date' => 'Date de l\'Action',
                'company' => 'Entreprise',
            ],
        ],
    ],

    'status' => [
        'upcoming' => 'À Venir',
        'ongoing' => 'En Cours',
        'past' => 'Passé',
    ],
];
