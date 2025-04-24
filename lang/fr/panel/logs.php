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

        // Visitor events
        'visitor_submitted' => 'Inscription de visiteur',

        // Exhibitor events
        'exhibitor_submitted' => 'Inscription d\'exposant',
        'exhibitor_uploaded_payment_proof' => 'Téléchargement de preuve de paiement',
        'exhibitor_submitted_post_forms' => 'Soumission de formulaire post-paiement',
        'exhibitor_requested_update' => 'Demande de mise à jour',
        'exhibitor_updated_submission' => 'Mise à jour de soumission',
        'exhibitor_downloaded_invoice' => 'Téléchargement de facture',

        // Admin actions on exhibitor submissions
        'exhibitor_submission_accepted' => 'Soumission acceptée',
        'exhibitor_submission_rejected' => 'Soumission refusée',
        'exhibitor_payment_validated' => 'Paiement validé',
        'exhibitor_payment_rejected' => 'Paiement refusé',
        'exhibitor_payment_slice_deleted' => 'Tranche de paiement supprimée',
        'exhibitor_submission_marked_ready' => 'Marqué comme prêt',
        'exhibitor_submission_archived' => 'Soumission archivée',
        'exhibitor_update_request_approved' => 'Demande de mise à jour approuvée',
        'exhibitor_update_request_denied' => 'Demande de mise à jour refusée',
    ],
    'exhibitor_submissions' => [
        'create' => 'Soumission de formulaire',
        'update' => 'Mise à jour de formulaire',
        'payment_proof' => 'Soumission de preuve de paiement',
        'post_form' => 'Soumission de formulaire post-paiement',
        'old_data' => 'Données anciennes',
        'new_data' => 'Données nouvelles',
    ],
    'visitor_submissions' => [
        'create' => 'Soumission d\'inscription',
        'update' => 'Mise à jour d\'inscription',
        'attendance' => 'Enregistrement de présence',
        'old_data' => 'Données anciennes',
        'new_data' => 'Données nouvelles',
    ],
    'names' => [
        'categories' => 'Catégories',
        'articles' => 'Articles',
        'authentication' => 'Authentification',
        'event_announcements' => 'Annonces d\'événements',
        'products' => 'Produits',
        'plans' => 'Plans',
        'plan_tiers' => 'Plans Sponsoring',
        'visitor_submissions' => 'Inscriptions Visiteurs',
        'exhibitor_submissions' => 'Inscriptions Exposants',
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
