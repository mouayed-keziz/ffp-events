<?php

return [
    'events' => [
        'creation' => 'Creation',
        'modification' => 'Modification',
        'deletion' => 'Deletion',
        'force_deletion' => 'Force Deletion',
        'restoration' => 'Restoration',
        'login' => 'Login',
        'logout' => 'Logout',
    ],
    'resource' => [
        'single' => 'Log',
        'plural' => 'Logs',
    ],
    'names' => [
        'categories' => 'Categories',
        'articles' => 'Articles',
        'authentication' => 'Authentication',
    ],
    'columns' => [
        "log_name" => "Log Name",
        "subject" => "Subject",
        "causer" => "Causer",
        "event" => "Event",
        "created_at" => "Created At",
    ],
    'empty_states' => [
        'log_name' => 'No log specified.',
        'subject' => 'no subject',
        'causer' => 'no causer',
        'event' => 'no event',
        "created_at" => "no creation date",
        'deleted_record' => '*Deleted Record*',
    ],
    'filters' => [
        'date' => [
            'from' => 'Start Date',
            'until' => 'End Date',
            'empty_state' => [
                'from' => 'No start date',
                'until' => 'No end date'
            ]
        ],
        'log_name' => 'Log Type',
        'event' => 'Event Type'
    ],
    'actions' => [
        'export' => [
            "label" => "Export logs",
        ],
        'delete_all' => [
            'label' => 'Delete all logs',
            'modal' => [
                'heading' => 'Delete all logs',
                'description' => 'Are you sure you want to delete all logs? This action cannot be undone.',
                'submit_label' => 'Yes, delete all logs',
                'password' => [
                    'label' => 'Your password',
                    'helper_text' => 'Please enter your password to confirm this action'
                ]
            ],
            'notifications' => [
                'success' => [
                    'title' => 'Logs deleted successfully',
                    'body' => 'All logs have been permanently deleted.'
                ],
                'error' => [
                    'title' => 'Incorrect password',
                    'body' => 'The password you entered is incorrect.'
                ]
            ]
        ]
    ],

    "export" => []
];
