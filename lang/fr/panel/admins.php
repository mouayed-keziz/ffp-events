<?php

return [
    'resource' => [
        'single' => 'Utilisateur FFP',
        'plural' => 'Utilisateurs FFP',
    ],
    'columns' => [
        'id' => 'ID',
        'name' => 'Nom',
        'email' => 'Email',
        'roles' => 'Rôles',
        'created_at' => 'Créé le',
        'updated_at' => 'Mis à jour le',
        'verified_at' => 'Vérifié',
    ],
    'form' => [
        'name' => 'Nom',
        'email' => 'Email',
        'password' => 'Mot de passe',
        'roles' => 'Rôles',
        'assigned_events' => 'Événements assignés',
    ],
    'empty_states' => [
        'title' => 'Aucun utilisateur FFP',
        'description' => 'Commencez par créer un utilisateur FFP.',
        'name' => 'Aucun nom fourni',
        'email' => 'Aucun email fourni',
        'roles' => 'Aucun rôle assigné',
        "assigned_events" => 'Aucun événement assigné',
    ],
    'fields' => [
        'name' => 'Nom',
        'email' => 'Email',
        'roles' => 'Rôles',
        'verified_at' => 'Vérifié le',
        'created_at' => 'Créé le',
        'updated_at' => 'Mis à jour le',
    ],
    'placeholders' => [
        'name' => 'Aucun nom fourni',
        'email' => 'Aucun email fourni',
        'roles' => 'Aucun rôle attribué',
        'assigned_events' => 'Aucun événement assigné',
    ],
    'filters' => [
        'roles' => [
            'label' => 'Rôles',
            'placeholder' => 'Sélectionner les rôles',
        ],
        'verification' => [
            'label' => 'Statut de vérification',
            'placeholder' => 'Sélectionner le statut',
            'verified' => 'Vérifié',
            'unverified' => 'Non vérifié',
        ],
        'trashed' => [
            'label' => 'Utilisateurs FFP supprimés',
            'placeholder' => 'Sélectionner le statut de suppression',
            'with_trashed' => 'Avec supprimés',
            'only_trashed' => 'Uniquement supprimés',
            'without_trashed' => 'Sans supprimés',
        ],
    ],
    'actions' => [
        "regenerate_password" => [
            "label" => "Régénérer le mot de passe",
            "modal_heading" => "Régénérer le mot de passe",
            "modal_description" => "Êtes-vous sûr de vouloir régénérer le mot de passe de cet utilisateur FFP ? Il recevra un email avec son nouveau mot de passe.",
            "success_title" => "Mot de passe régénéré",
            "success_body" => "L'utilisateur FFP recevra un email avec son nouveau mot de passe.",
            "error_title" => "Échec de la régénération du mot de passe",
            "error_body" => "Une erreur s'est produite lors de la régénération du mot de passe."
        ]
    ],
    'tabs' => [
        'all' => 'Tous les utilisateurs FFP',
        'admin' => 'Administrateurs',
        'super_admin' => 'Super administrateurs',
        'hostess' => 'Hôtesses',
        'deleted' => 'Supprimés'
    ]
];
