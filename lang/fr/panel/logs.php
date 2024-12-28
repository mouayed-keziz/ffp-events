<?php

return [
    'events' => [
        'creation' => 'Création',
        'modification' => 'Modification',
        'deletion' => 'Suppression',

        'login' => 'Connexion',
        'logout' => 'Déconnexion',
    ],
    "names" => [
        'categories' => 'Catégories',
        'articles' => 'Articles',
        'authentication' => 'Authentification',
    ],
    'resource' => [
        'single' => 'Journal',
        'plural' => 'Journaux',
    ],
    'columns' => [
        "log_name" => "Nom du journal",
        "subject" => "Sujet",
        "causer" => "Causer",
        "event" => "Événement",
        "created_at" => "Créé à",
    ],
    'empty_states' => [
        'log_name' => 'Aucun journal spécifié.',
        'subject' => 'sans sujet',
        'causer' => 'sans causer',
        'event' => 'sans événement',
        "created_at" => "sans date de création",

        'deleted_record' => '*Enregistrement supprimé*',
    ],

    'filters' => [
        'date' => [
            'from' => 'Date de début',
            'until' => 'Date de fin',
            'empty_state' => [
                'from' => 'Aucune date de début',
                'until' => 'Aucune date de fin'
            ]
        ],
        'log_name' => 'Type de journal',
        'event' => 'Type d\'événement'
    ]
];
