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
    ],

    'empty' => [
        'title' => 'Aucune exportation',
        'description' => 'Commencez par créer une exportation.',
    ],
];
