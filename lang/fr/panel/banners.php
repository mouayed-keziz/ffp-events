<?php

return [
    'resource' => [
        'single' => 'Bannière',
        'plural' => 'Bannières',
    ],
    'form' => [
        'sections' => [
            'general' => 'Informations Générales',
            'image' => 'Image de Bannière',
        ],
        'fields' => [
            'title' => 'Titre',
            'url' => 'URL',
            'order' => 'Ordre d\'Affichage',
            'is_active' => 'Actif',
            'image' => 'Image de Bannière',
        ],
    ],
    'table' => [
        'columns' => [
            'image' => 'Image',
            'title' => 'Titre',
            'url' => 'URL',
            'order' => 'Ordre',
            'is_active' => 'Actif',
            'created_at' => 'Créé le',
            'updated_at' => 'Mis à jour le',
        ],
        'actions' => [
            'preview' => 'Prévisualiser le lien',
        ],
    ],
    'notifications' => [
        'created' => [
            'title' => 'Bannière créée',
            'body' => 'La bannière a été créée avec succès.',
        ],
        'updated' => [
            'title' => 'Bannière mise à jour',
            'body' => 'La bannière a été mise à jour avec succès.',
        ],
        'deleted' => [
            'title' => 'Bannière supprimée',
            'body' => 'La bannière a été supprimée avec succès.',
        ],
    ],
];
