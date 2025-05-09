<?php

return [
    'resource' => [
        'single' => 'Exposant',
        'plural' => 'Exposants',
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
    ],
    'empty_states' => [
        'title' => 'Aucun exposant',
        'description' => 'Commencez par créer un exposant.',
        'name' => 'Aucun nom fourni',
        'email' => 'Aucun email fourni',
        'roles' => 'Aucun rôle assigné',
    ],
    'tabs' => [
        'all' => 'Tous les exposants',
        'deleted' => 'Exposants supprimés',
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
    ],
    'actions' => [
        "regenerate_password" => [
            "label" => "Régénérer le mot de passe",
            "modal_heading" => "Régénérer le mot de passe",
            "modal_description" => "Êtes-vous sûr de vouloir régénérer le mot de passe de cet exposant ? Il recevra un email avec son nouveau mot de passe.",
            "success_title" => "Mot de passe régénéré",
            "success_body" => "L'exposant recevra un email avec son nouveau mot de passe.",
            "error_title" => "Échec de la régénération du mot de passe",
            "error_body" => "Une erreur s'est produite lors de la régénération du mot de passe."
        ]
    ]
];
