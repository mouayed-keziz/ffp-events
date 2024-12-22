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
    ],
    'form' => [
        'title' => 'Title',
        'description' => 'Description',
        'content' => 'Content',
        "published_date" => "Publication date",
    ],
    'empty_states' => [
        'title' => "None",
        'description' => "None",
        'content' => "None",
        'deleted_at' => "Not deleted",
        'published_at' => "Not published",
        'created_at' => "Not created",
        'updated_at' => "Not updated",
    ],
    'placeholders' => [
        'title' => 'Enter title',
        'description' => 'Enter description',
        'content' => 'Enter content',
    ],
    'status' => [
        "all" => "All",
        "draft" => "Draft",
        "pending" => "Pending",
        "published" => "Published",
        "deleted" => "Deleted",
    ],
    'actions' => []
];
