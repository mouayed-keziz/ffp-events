<?php

return [
    'resource' => [
        'single' => 'Admin',
        'plural' => 'Admins',
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
    ],
    'empty_states' => [
        'title' => 'No admins yet',
        'description' => 'Create an admin to get started',
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
            'label' => 'Deleted Admins',
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
            "modal_description" => "Are you sure you want to regenerate this admin's password? They will receive an email with their new password.",
            "success_title" => "Password regenerated",
            "success_body" => "The admin will receive an email with their new password.",
            "error_title" => "Failed to regenerate password",
            "error_body" => "An error occurred while regenerating the password."
        ]
    ],
    'tabs' => [
        'all' => 'All Users',
        'admin' => 'Admins',
        'super_admin' => 'Super Admins',
        'deleted' => 'Deleted'
    ]
];
