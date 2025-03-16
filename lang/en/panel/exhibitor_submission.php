<?php

return [
    'resource' => [
        'label' => 'Soumission Exposant',
        'plural_label' => 'Soumissions Exposants',
    ],
    'fields' => [
        'status' => 'Statut',
        'total_prices' => 'Prix Totaux',
        'answers' => 'Réponses du Formulaire',
        'created_at' => 'Soumis le',
        'exhibitor' => 'Exposant',
        'event' => 'Événement',
        'attachments' => 'Pièces Jointes',
    ],
    'filters' => [
        'status' => 'Filtrer par Statut',
        'event' => 'Filtrer par Événement',
        'date_range' => 'Filtrer par Période',
    ],
    'actions' => [
        'delete' => 'Supprimer',
        'view' => 'Voir Détails',
        'update' => 'Mettre à jour',
        'download' => 'Télécharger le Fichier',
    ],
    'messages' => [
        'deleted' => 'Soumission supprimée avec succès',
        'updated' => 'Soumission mise à jour avec succès',
    ]
];
