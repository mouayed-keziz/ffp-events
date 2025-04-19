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
        'register' => 'Registration',

        // Visitor events
        'visitor_submitted' => 'Visitor Registration',

        // Exhibitor events
        'exhibitor_submitted' => 'Exhibitor Registration',
        'exhibitor_uploaded_payment_proof' => 'Payment Proof Upload',
        'exhibitor_submitted_post_forms' => 'Post-Payment Form Submission',
        'exhibitor_requested_update' => 'Update Request',
        'exhibitor_updated_submission' => 'Submission Update',
        'exhibitor_downloaded_invoice' => 'Invoice Download',

        // Admin actions on exhibitor submissions
        'exhibitor_submission_accepted' => 'Submission Accepted',
        'exhibitor_submission_rejected' => 'Submission Rejected',
        'exhibitor_payment_validated' => 'Payment Validated',
        'exhibitor_payment_rejected' => 'Payment Rejected',
        'exhibitor_payment_slice_deleted' => 'Payment Slice Deleted',
        'exhibitor_submission_marked_ready' => 'Marked as Ready',
        'exhibitor_submission_archived' => 'Submission Archived',
        'exhibitor_update_request_approved' => 'Update Request Approved',
        'exhibitor_update_request_denied' => 'Update Request Denied',
    ],
    'exhibitor_submissions' => [
        'create' => 'Form submission',
        'update' => 'Form update',
        'payment_proof' => 'Payment proof submission',
        'post_form' => 'Post-payment form submission',
        'old_data' => 'Previous data',
        'new_data' => 'New data',
    ],
    'visitor_submissions' => [
        'create' => 'Registration submission',
        'update' => 'Registration update',
        'attendance' => 'Attendance registration',
        'old_data' => 'Previous data',
        'new_data' => 'New data',
    ],
    'resource' => [
        'single' => 'Log',
        'plural' => 'Logs',
    ],
    'names' => [
        'categories' => 'Categories',
        'articles' => 'Articles',
        'authentication' => 'Authentication',
        'event_announcements' => 'Event Announcements',
        'products' => 'Products',
        'plans' => 'Plans',
        'plan_tiers' => 'Plan Tiers',
        'visitor_submissions' => 'Visitor Submissions',
        'exhibitor_submissions' => 'Exhibitor Submissions',
        'company_information' => 'Company Information',
        'banners' => 'Banners',
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
