<?php

return [
    'events' => [
        'creation' => 'Creation',
        'modification' => 'Modification',
        'deletion' => 'Deletion',
    ],
    'resource' => [
        'single' => 'Log',
        'plural' => 'Logs',
    ],
    'names' => [
        'categories' => 'Categories',
        'articles' => 'Articles',
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
    ]
];
