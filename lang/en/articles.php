<?php

return [
    'resource' => [
        'single' => 'Article',
        'plural' => 'Articles',
    ],
    'columns' => [
        'id' => 'ID',
        'title' => 'Title',
        'description' => 'Description',
        'content' => 'Content',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',
        'deleted_at' => 'Deleted at',
        'slug' => 'Slug',
        "status" => "Status",
        'image' => 'Image',
    ],
    'form' => [
        'title' => 'Title',
        'description' => 'Description',
        'content' => 'Content',
        "published_date" => "Publication date",
        'slug' => 'Slug',
        'tabs' => [
            'information' => 'Information',
            'content' => 'Content',
        ],
        'sections' => [
            'featured_image' => 'Featured Image',
        ],
    ],
    'empty_states' => [
        'title' => "None",
        'description' => "None",
        'content' => "None",
        'deleted_at' => "Not deleted",
        'published_at' => "Not published",
        'created_at' => "Not created",
        'updated_at' => "Not updated",
        'slug' => "None",
        'image' => 'No image',
    ],
    'placeholders' => [
        'title' => 'Enter title',
        'description' => 'Enter description',
        'content' => 'Enter content',
        'slug' => 'Enter slug',
    ],
    'status' => [
        "all" => "All",
        "draft" => "Draft",
        "pending" => "Pending",
        "published" => "Published",
        "deleted" => "Deleted",
    ],
    'actions' => [
        "visit" =>  "Visit",
    ],
    "categories" => [
        "single" => "Category",
        "plural" => "Categories",
        "articles" => "Articles",
        "fields" => [
            "name" => "Name",
            "slug" => "Slug",
        ],
    ]
];
