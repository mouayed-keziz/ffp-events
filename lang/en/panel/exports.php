<?php

return [
    'single' => 'Export',
    'plural' => 'Exports',
    'title' => 'Exports',
    'description' => 'View and manage exports',

    'columns' => [
        'id' => 'ID',
        'completed_at' => 'Completed At',
        'file_name' => 'File Name',
        'exporter' => 'Exporter',
        'processed_rows' => 'Processed Rows',
        'total_rows' => 'Total Rows',
        'successful_rows' => 'Successful Rows',
        'exported_by' => 'Exported By',
    ],

    'filters' => [
        'date' => [
            'start' => 'Start Date',
            'end' => 'End Date',
            'placeholder' => 'Select date...',
        ],
        'type' => [
            'label' => 'Export Type',
            'placeholder' => 'Select export type...',
        ],
    ],

    'empty' => [
        'title' => 'No exports yet',
        'description' => 'Start by creating an export.',
    ],

    'type' => [
        'log' => 'Log Export',
        'article' => 'Article Export',
        'event_announcement' => 'Event Announcement Export',
        'user' => 'User Export',
        'category' => 'Category Export',
        'exhibitor' => 'Exhibitor Export',
        'product' => 'Product Export',
        'visitor' => 'Visitor Export',
        "export" => 'Export Export',
        'exhibitor_submission' => "Exhibitor Submission Export",
        'visitor_submission' =>  "Visitor Submission Export",
    ],
];
