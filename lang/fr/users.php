<?php

return [
    'resource' => [
        'single' => 'Utilisateur',
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
        'verified_at' => 'Vérifié le',
    ],
    'form' => [
        'name' => 'Nom',
        'email' => 'Email',
        'password' => 'Mot de passe',
    ],
    'empty_states' => [
        'title' => 'Aucun utilisateur',
        'description' => 'Créez un utilisateur pour commencer',
        'name' => 'Aucun nom fourni',
        'email' => 'Aucun email fourni',
        'roles' => 'Aucun rôle assigné',
    ],
];
