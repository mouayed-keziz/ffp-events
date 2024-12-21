<?php

return [
    'resource' => [
        'single' => 'User',
        'plural' => 'Users',
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
        'title' => 'No users yet',
        'description' => 'Create a user to get started',
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
            'label' => 'Deleted Users',
            'placeholder' => 'Select deleted status',
            'with_trashed' => 'With deleted',
            'only_trashed' => 'Only deleted',
            'without_trashed' => 'Without deleted',
        ],
    ],
    'stats' => [
        'total_users' => 'Total Users',
        'new_users' => 'New Users',
        'verified_users' => 'Verified Users',
        'last_30_days' => 'Last 30 days',
    ],
    'actions' => [
        "regenerate_password" => [
            "label" => "Regenerate password",
            "modal_heading" => "Regenerate password",
            "modal_description" => "Are you sure you want to regenerate this user's password? They will receive an email with their new password.",
            "success_title" => "Password regenerated",
            "success_body" => "The user will receive an email with their new password.",
            "error_title" => "Password regeneration failed",
            "error_body" => "An error occurred while regenerating the password."
        ]
    ]
];
