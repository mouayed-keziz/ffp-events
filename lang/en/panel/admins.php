<?php

return [
    'resource' => [
        'single' => 'FFP User',
        'plural' => 'FFP Users',
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
        'roles' => 'Roles',
        'assigned_events' => 'Assigned Events',
    ],
    'empty_states' => [
        'title' => 'No FFP users yet',
        'description' => 'Create an FFP user to get started',
        'name' => 'No name provided',
        'email' => 'No email provided',
        'roles' => 'No roles assigned',
        'assigned_events' => 'No events assigned',
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
            'label' => 'Deleted FFP Users',
            'placeholder' => 'Select deleted status',
            'with_trashed' => 'With deleted',
            'only_trashed' => 'Only deleted',
            'without_trashed' => 'Without deleted',
        ],
    ],
    'actions' => [
        "regenerate_password" => [
            "label" => "Regenerate password",
            "modal_heading" => "Regenerate password",
            "modal_description" => "Are you sure you want to regenerate this FFP user's password? They will receive an email with their new password.",
            "success_title" => "Password regenerated",
            "success_body" => "The FFP user will receive an email with their new password.",
            "error_title" => "Failed to regenerate password",
            "error_body" => "An error occurred while regenerating the password."
        ]
    ],
    'tabs' => [
        'all' => 'All FFP Users',
        'admin' => 'Admins',
        'super_admin' => 'Super Admins',
        'hostess' => 'Hostesses',
        'deleted' => 'Deleted'
    ]
];
