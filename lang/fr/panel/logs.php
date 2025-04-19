<?php

return [
    'events' => [
        'creation' => 'Création',
        'modification' => 'Modification',
        'deletion' => 'Suppression',

        'force_deletion' => 'Suppression forcée',
        'restoration' => 'Restauration',

        'login' => 'Connexion',
        'logout' => 'Déconnexion',
        'register' => 'Inscription',
    ],
    'names' => [
        'categories' => 'Catégories',
        'articles' => 'Articles',
        'authentication' => 'Authentification',
        'event_announcements' => 'Annonces d\'événements',
        'products' => 'Produits',
        'plans' => 'Plans',
        'plan_tiers' => 'Niveaux de plan',
        'visitor_submissions' => 'Soumissions visiteurs',
        'exhibitor_submissions' => 'Soumissions exposants',
        'company_information' => 'Informations sur l\'entreprise',
        'banners' => 'Bannières',
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
    ],

    'actions' => [
        'export' => [
            "label" => "Exporter les journaux",
        ],
        'delete_all' => [
            'label' => 'Supprimer tous les journaux',
            'modal' => [
                'heading' => 'Supprimer tous les journaux',
                'description' => 'Êtes-vous sûr de vouloir supprimer tous les journaux ? Cette action ne peut pas être annulée.',
                'submit_label' => 'Oui, supprimer tous les journaux',
                'password' => [
                    'label' => 'Votre mot de passe',
                    'helper_text' => 'Veuillez saisir votre mot de passe pour confirmer cette action'
                ]
            ],
            'notifications' => [
                'success' => [
                    'title' => 'Journaux supprimés avec succès',
                    'body' => 'Tous les journaux ont été définitivement supprimés.'
                ],
                'error' => [
                    'title' => 'Mot de passe incorrect',
                    'body' => 'Le mot de passe que vous avez saisi est incorrect.'
                ]
            ]
        ]
    ],
];
