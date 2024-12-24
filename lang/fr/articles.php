<?php

return [
    'resource' => [
        'single' => 'Article',
        'plural' => 'Articles',
    ],
    'columns' => [
        'id' => 'ID',
        'title' => 'Titre',
        'slug' => 'Slug',
        'description' =>  'Description',
        'content' =>  'Contenu',
        'created_at' => 'Créé le',
        'updated_at' => 'Mis à jour le',
        'deleted_at' =>  'Supprimé le',
        "status" => "Statut",
        'image' => 'Image',
    ],
    'form' => [
        'title' =>  'Titre',
        'slug' => 'Slug',
        'description' =>  'Description',
        'content' => 'Contenu',
        "published_date" => "Date de publication",
        'tabs' => [
            'information' => 'Informations',
            'content' => 'Contenu',
        ],
        'sections' => [
            'featured_image' => 'Image à la une',
        ],
    ],
    'empty_states' => [
        'title' =>  "Sans titre",
        'slug' => "Sans slug",
        'description' => "Sans description",
        'contenu' => "Sans contenu",
        'deleted_at' => "Non supprimé",
        'published_at' => "Non publié",
        "created_at" => "Non créé",
        "updated_at" => "Non mis à jour",
        'image' =>  'Sans image',
    ],
    'placeholders' => [
        'title' => 'Entrez le titre',
        'slug' => 'Entrez le slug',
        'description' => 'Entrez la description',
        'content' => 'Entrez le contenu',
    ],
    'status' => [
        "all" => "Tous",
        "draft" => "Brouillon",
        "pending" => "En attente",
        "published" => "Publié",
        "deleted" => "Supprimé",
    ],
    'actions' => [
        "visit" =>  "Visiter",
    ],

    "categories" => [
        "single" => "Catégorie",
        "plural" => "Catégories",
        "articles" => "Articles",
        "fields" => [
            "name" => "Nom",
            "slug" => "Slug",
        ],
    ]
];
