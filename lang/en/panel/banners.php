<?php

return [
    'resource' => [
        'single' => 'Banner',
        'plural' => 'Banners',
    ],
    'form' => [
        'sections' => [
            'general' => 'General Information',
            'image' => 'Banner Image',
        ],
        'fields' => [
            'title' => 'Title',
            'url' => 'URL',
            'order' => 'Display Order',
            'is_active' => 'Active',
            'image' => 'Banner Image',
        ],
    ],
    'table' => [
        'columns' => [
            'image' => 'Image',
            'title' => 'Title',
            'url' => 'URL',
            'order' => 'Order',
            'is_active' => 'Active',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ],
        'actions' => [
            'preview' => 'Preview Link',
        ],
    ],
    'notifications' => [
        'created' => [
            'title' => 'Banner created',
            'body' => 'The banner has been created successfully.',
        ],
        'updated' => [
            'title' => 'Banner updated',
            'body' => 'The banner has been updated successfully.',
        ],
        'deleted' => [
            'title' => 'Banner deleted',
            'body' => 'The banner has been deleted successfully.',
        ],
    ],
];
