<?php

return [
    'resource' => [
        'single' => 'Visitor',
        'plural' => 'Visitors',
    ],
    'columns' => [
        'id' => 'ID',
        'name' => 'Name',
        'email' => 'Email',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'verified_at' => 'Verified',
        'roles' => 'Roles',
    ],
    'form' => [
        'name' => 'Name',
        'email' => 'Email',
        'password' => 'Password',
    ],
    'empty_states' => [
        'title' => 'No visitors yet',
        'description' => 'Create a visitor to get started',
        'name' => 'No name provided',
        'email' => 'No email provided',
        'roles' => 'No roles assigned',
    ],
    'filters' => [
        'roles' => [
            'label' => 'Roles',
            'placeholder' => 'Select roles',
        ],
        'verification' => [
            'label' => 'Verification Status',
            'placeholder' => 'Select status',
            'verified' => 'Verified',
            'unverified' => 'Unverified',
        ],
        'trashed' => [
            'label' => 'Deleted Visitors',
            'placeholder' => 'Select deleted status',
            'with_trashed' => 'With deleted',
            'only_trashed' => 'Only deleted',
            'without_trashed' => 'Without deleted',
        ],
    ],
    'stats' => [
        'total_users' => 'Total Visitors',
        'new_users' => 'New Visitors',
        'verified_users' => 'Verified Visitors',
        'last_30_days' => 'Last 30 days',
    ],
    'actions' => [
        "regenerate_password" => [
            "label" => "Regenerate password",
            "modal_heading" => "Regenerate password",
            "modal_description" => "Are you sure you want to regenerate this visitor's password? They will receive an email with their new password.",
            "success_title" => "Password regenerated",
            "success_body" => "The visitor will receive an email with their new password.",
            "error_title" => "Password regeneration failed",
            "error_body" => "An error occurred while regenerating the password."
        ]
    ]
];
