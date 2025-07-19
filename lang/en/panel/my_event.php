<?php

return [
    'resource' => [
        'label' => 'My Event',
        'plural_label' => 'My Events',
    ],

    'navigation' => [
        'group' => 'My Events',
    ],

    'fields' => [
        'title' => 'Title',
        'description' => 'Description',
        'location' => 'Location',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
        'visitor_registration_start_date' => 'Visitor Registration Start',
        'visitor_registration_end_date' => 'Visitor Registration End',
        'exhibitor_registration_start_date' => 'Exhibitor Registration Start',
        'exhibitor_registration_end_date' => 'Exhibitor Registration End',
        'website_url' => 'Website URL',
        'contact' => 'Contact',
        'currencies' => 'Currencies',
        'image' => 'Event Image',
        'terms' => 'Terms and Conditions',
        'content' => 'Content',
    ],

    'actions' => [
        'view' => 'View Event',
        'edit' => 'Edit Event',
    ],

    'infolist' => [
        'sections' => [
            'basic_info' => 'Basic Information',
            'dates' => 'Event Dates',
            'registration_dates' => 'Registration Periods',
            'additional_info' => 'Additional Information',
        ],
    ],

    'table' => [
        'columns' => [
            'title' => 'Title',
            'location' => 'Location',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'status' => 'Status',
        ],
        'filters' => [
            'status' => 'Status',
            'date_range' => 'Date Range',
        ],
    ],

    'relation_managers' => [
        'current_attendees' => [
            'title' => 'Current Attendees',
            'columns' => [
                'badge_name' => 'Name',
                'badge_email' => 'Email',
                'badge_company' => 'Company',
                'badge_position' => 'Position',
                'checked_in_at' => 'Checked In At',
                'checked_in_by_user' => 'Checked In By',
            ],
            'filters' => [
                'checked_in_date' => 'Check-in Date',
                'company' => 'Company',
                'position' => 'Position',
            ],
        ],
        'badge_check_logs' => [
            'title' => 'Badge Check Logs',
            'columns' => [
                'badge_name' => 'Name',
                'badge_email' => 'Email',
                'badge_company' => 'Company',
                'action' => 'Action',
                'action_time' => 'Action Time',
                'checked_by_user' => 'Checked By',
                'note' => 'Note',
            ],
            'filters' => [
                'action' => 'Action',
                'action_date' => 'Action Date',
                'company' => 'Company',
            ],
        ],
    ],

    'status' => [
        'upcoming' => 'Upcoming',
        'ongoing' => 'Ongoing',
        'past' => 'Past',
    ],
];
