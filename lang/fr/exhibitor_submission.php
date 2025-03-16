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
        'payment_slice' => [
            'status' => 'Statut',
            'price' => 'Montant',
            'sort' => 'Ordre',
            'currency' => 'Devise',
            'attachment' => 'Preuve de Paiement',
        ],
    ],
    'filters' => [
        'status' => 'Filtrer par Statut',
        'event' => 'Filtrer par Événement',
        'date_range' => 'Filtrer par Période',
        'payment_slice' => [
            'status' => 'Filtrer par Statut',
        ],
    ],
    'actions' => [
        'delete' => 'Supprimer',
        'view' => 'Voir Détails',
        'update' => 'Mettre à jour',
        'download' => 'Télécharger le Fichier',
        'payment_slice' => [
            'create' => 'Ajouter une Tranche',
        ],
    ],
    'messages' => [
        'deleted' => 'Soumission supprimée avec succès',
        'updated' => 'Soumission mise à jour avec succès',
    ],
    'sections' => [
        'payment_slices' => 'Tranches de Paiement',
    ],
];
