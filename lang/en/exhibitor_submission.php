<?php

return [
    'resource' => [
        'label' => 'Exhibitor Submission',
        'plural_label' => 'Exhibitor Submissions',
    ],
    'fields' => [
        'status' => 'Status',
        'total_prices' => 'Total Prices',
        'answers' => 'Form Answers',
        'created_at' => 'Submitted At',
        'exhibitor' => 'Exhibitor',
        'event' => 'Event',
        'attachments' => 'Attachments',
        'payment_slice' => [
            'status' => 'Status',
            'price' => 'Amount',
            'sort' => 'Order',
            'currency' => 'Currency',
            'attachment' => 'Payment Proof',
            'due_to' => 'Due Date',
        ],
    ],
    'filters' => [
        'status' => 'Filter by Status',
        'event' => 'Filter by Event',
        'date_range' => 'Filter by Date Range',
        'payment_slice' => [
            'status' => 'Filter by Status',
        ],
    ],
    'actions' => [
        'delete' => 'Delete',
        'view' => 'View Details',
        'update' => 'Update',
        'download' => 'Download File',
        'payment_slice' => [
            'create' => 'Add Payment Slice',
            'create_title' => 'Add Exhibitor Payment Slice',
        ],
    ],
    'messages' => [
        'deleted' => 'Submission deleted successfully',
        'updated' => 'Submission updated successfully',
    ],
    'sections' => [
        'payment_slices' => 'Payment Slices',
    ],
    'empty' => [
        'payment_slices' => 'No payment slices',
        'payment_slices_description' => 'You can add payment slices to this submission.',
    ],
];
