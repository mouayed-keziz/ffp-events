<?php

return [
    'resource' => [
        'label' => 'Utilisateur',
        'plural' => 'Utilisateurs',
        'navigation_group' => 'Gestion',
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
        'title' => 'Aucun utilisateur',
        'description' => 'Commencez par créer un utilisateur.',
        'name' => 'Aucun nom fourni',
        'email' => 'Aucun email fourni',
        'roles' => 'Aucun rôle assigné',
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
        'trashed' => [
            'label' => 'Utilisateurs supprimés',
            'placeholder' => 'Sélectionner le statut de suppression',
            'with_trashed' => 'Avec supprimés',
            'only_trashed' => 'Uniquement supprimés',
            'without_trashed' => 'Sans supprimés',
        ],
    ],
    'stats' => [
        'total_users' => 'Total Utilisateurs',
        'new_users' => 'Nouveaux Utilisateurs',
        'verified_users' => 'Utilisateurs Vérifiés',
        'last_30_days' => 'Les 30 derniers jours',
    ],
];
