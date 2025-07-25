<?php

return [
    'single' => 'Exportation',
    'plural' => 'Exportations',
    'title' => 'Exportations',
    'description' => 'Voir et gérer les exportations',

    'columns' => [
        'id' => 'ID',
        'completed_at' => 'Terminé le',
        'file_name' => 'Nom du fichier',
        'exporter' => 'Exportateur',
        'processed_rows' => 'Lignes traitées',
        'total_rows' => 'Lignes totales',
        'successful_rows' => 'Lignes réussies',
        'exported_by' => 'Exporté par',
    ],

    'filters' => [
        'date' => [
            'start' => 'Date de début',
            'end' => 'Date de fin',
            'placeholder' => 'Sélectionner une date...',
        ],
        'type' => [
            'label' => 'Type d\'exportation',
            'placeholder' => 'Sélectionner le type...',
        ],
    ],

    'empty' => [
        'title' => 'Aucune exportation',
        'description' => 'Commencez par créer une exportation.',
    ],

    'type' => [
        'log' => 'Export des journaux',
        'article' => 'Export des articles',
        'event_announcement' => 'Export des annonces d\'événement',
        'user' => 'Export des utilisateurs',
        'category' => 'Export des catégories',
        'exhibitor' => 'Export des exposants',
        'product' => 'Export des produits',
        'visitor' => 'Export des visiteurs',
        'export' => 'Export des exportations',
        'exhibitor_submission' => 'Export des soumissions des exposants',
        'visitor_submission' => 'Export des soumissions des visiteurs',

    ],
];
