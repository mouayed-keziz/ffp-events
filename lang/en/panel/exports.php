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
    ],

    'empty' => [
        'title' => 'No exports yet',
        'description' => 'Start by creating an export.',
    ],
];
